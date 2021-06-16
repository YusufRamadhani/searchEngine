<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\PreProcessText;
use App\Libraries\Decode;
use App\IndexTerm;
use App\Document;
use Illuminate\Support\Facades\DB;

ini_set('max_execution_time', 0);
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
        $this->document = new Document();
    }

    public function index()
    {
        return view('indexterm');
    }

    public function createIndex(Request $request)
    {
        $daterange = $this->dateRange($request);
        $setDocument = $this->document->setDocument($daterange);
        if ($setDocument === 'success') {
            $document = $this->getDocument($daterange);
        }
        $updated = 0;
        $created = 0;

        foreach ($document as $value) {
            $terms = $this->processText->preProcessText($value->chat);
            $term = array_unique($terms);
            $frequencyTerm = array_count_values($terms);
            $totalTerms = count($terms);
            $indexedTerm = json_decode(IndexTerm::pluck('term'));

            foreach ($term as $subvalue) {
                if (!empty($subvalue)) {
                    $content = array(
                        'idDocument' => $value->id,
                        'termFrequency' => $frequencyTerm[$subvalue],
                        'totalTerms' => $totalTerms
                    );
                    try {
                        if (in_array($subvalue, $indexedTerm)) {
                            # disini update
                            $indexTerm = IndexTerm::where('term', $subvalue)->first();
                            $contentArr = json_decode($indexTerm->content, true);
                            if (!in_array($content, $contentArr)) {
                                array_push($contentArr, $content);
                                $indexTerm->content = json_encode($contentArr);
                                $indexTerm->save();
                                $updated += 1;
                            }
                        } else {
                            # disini create
                            $indexTerm = new IndexTerm([
                                'term' => $subvalue,
                                'content' => json_encode(array($content))
                            ]);
                            $indexTerm->save();
                            $created += 1;
                        }
                    } catch (\Exception $e) {
                        report($e);
                    }
                }
            }
        }
        return redirect()->route('index.term');
        // return view('testing', ['data' => [$created, $updated]]);
    }


    private function getDocument(array $dateRange)
    {
        return DB::table('documents')->whereBetween('date', $dateRange)->get();
    }

    private function dateRange(Request $request)
    {
        $startDate = date_create($request->input('start'));
        $endDate = date_create($request->input('end'));
        $endDate = $endDate->modify('+1 day');
        $periodChat = array($startDate, $endDate);
        return $periodChat;
    }
}
