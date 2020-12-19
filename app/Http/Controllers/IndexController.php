<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateInterval;
use DatePeriod;
use App\Libraries\PreProcessText;
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
    }

    public function index()
    {
        return view('indexterm');
    }

    public function createIndex(Request $request)
    {
        $daterange = $this->dateRange($request);
        $document = $this->getDocument($daterange);

        foreach ($document as $value) {
            $terms = $this->processText->preProcessText($value->chat);
            $term = array_unique($terms);
            $filterChat = preg_replace('/[^a-zA-Z0-9 ]/', '', $value->chat);
            foreach ($term as $subvalue) {
                if (!empty($subvalue)) {
                    $content = json_encode([
                        'idDocument' => $value->id,
                        'termFrequency' => substr_count($filterChat, $subvalue),
                        'totalTerm' => str_word_count($filterChat)
                    ]);

                    $indexTerm = new IndexTerm([
                        'term' => $subvalue,
                        'content' => $content
                    ]);

                    $indexTerm->save();
                }
            }
        }
        return redirect()->route('admin.dashboard');
    }

    private function getDocument(array $dateRange)
    {
        return DB::table('documents')->whereIn('date', $dateRange)->get();
    }

    public function dateRange(Request $request)
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
