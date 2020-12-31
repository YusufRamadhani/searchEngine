<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateInterval;
use DatePeriod;
use App\Libraries\PreProcessText;
use App\Libraries\Decode;
use App\IndexTerm;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    /*
    Controller ini akan menangani fitur membuat indeks oleh admin
    ada 2 fungsi utama indeks
    1. Indeks Dokumen
        a. memperbarui indexDocument
        mengambil input file chat di sistem utama kantor dan merubah formatnya menjadi indeks Document
    2. Indeks Bags of Words
        a. membuat atau menambahkan index BoW dari indeks Document
    */

    public function __construct()
    {
        $this->processText = new PreProcessText();
        $this->decode = new Decode();
    }

    public function index()
    {
        return view('indexterm');
    }

    public function createIndex(Request $request)
    {
        $daterange = $this->dateRange($request);
        $document = $this->getDocument($daterange);
        $updated = 0;
        $created = 0;

        foreach ($document as $value) {
            $terms = $this->processText->preProcessText($value->chat);
            $filterChat = $this->processText->preProcessChat($value->chat);
            $term = array_unique($terms);
            $frequencyTerm = array_count_values($filterChat);

            $indexedTerm = json_decode(IndexTerm::pluck('term'));

            foreach ($term as $subvalue) {
                if (!empty($subvalue)) {
                    $content = array(
                        'idDocument' => $value->id,
                        'termFrequency' => $frequencyTerm[$subvalue],
                        'totalTerms' => count($filterChat)
                    );
                    try {
                        if (in_array($subvalue, $indexedTerm)) {
                            # disini update
                            $indexTerm = IndexTerm::select('term', 'content')->where('term', $subvalue)->first();
                            $contentArr = json_decode($indexTerm->content, true);
                            array_push($contentArr, $content);
                            DB::table('index_term')->where('term', $subvalue)->update(['content' => json_encode($contentArr)]);
                            $updated += 1;
                        } else {
                            // # disini create

                            $indexTerm = new IndexTerm([
                                'term' => $subvalue,
                                'content' => json_encode(array($content))
                            ]);
                            $indexTerm->save();
                            $created += 1;
                        }
                    } catch (\Throwable $th) {
                    }
                }
            }
        }
        return redirect()->route('admin.dashboard');
        // return view('testing', ['data' => [$created, $updated]]);
    }


    private function getDocument(array $dateRange)
    {
        return DB::table('documents')->whereIn('date', $dateRange)->get();
    }

    private function dateRange(Request $request)
    {
        /*
    dateRange = [
        Y-m-d
    ]
    */
        $startDate = date_create_from_format("m-d-Y", $request->input('start'));
        $endDate = date_create_from_format("m-d-Y", $request->input('end'));
        $interval = new DateInterval('P1D');
        $period = new DatePeriod($startDate, $interval, $endDate);
        $periodChat = array();
        foreach ($period as $value) {
            $periodChat[] = $value->format('Y-m-d');
        }
        return $periodChat;
    }
}
