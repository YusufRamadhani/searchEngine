<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\PreProcessText;
use App\Libraries\Decode;
use App\SearchChat;
use App\IndexTerm;
use DateInterval;
use DatePeriod;
use Illuminate\Support\Facades\DB;


class SearchController extends Controller
{
    /*
    Controller ini menjadi controller utama sistem
    fungsi controller ini
    1. menampilkan home
    2. melakukan pencarian
    2.1. melakukan pencarian dengan rentang waktu
    3. memanggil hal login admin
    */

    private $preProcessText, $indexTerm, $search;

    public function __construct()
    {
        $this->processText = new PreProcessText();
        $this->search = new SearchChat();
        $this->decode = new Decode();
    }

    public function index()
    {
        return view('mainpage');
    }

    public function show($loglivechatid)
    {
        $dataChat = $this->search->showChat($loglivechatid);
        return view('chatdisplay', ['result' => $dataChat]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $daterange = null;
        if (null !== $request->input('start')) {
            $daterange = $this->dateRange($request);
            $success = $this->createIndexSearchWithDate($daterange);
            if ($success) {
                $data = DB::table('index_term_temporary')->select('term', 'content')->get();
                $indexTerm = $this->decode->decode($data);
                DB::table('index_term_temporary')->truncate();
            }
        } else {
            $data = IndexTerm::all(['term', 'content']);
            $indexTerm = $this->decode->decode($data);
        }
        // query disini nanti diperbaiki menggunakan preprocesschat
        $queryTerm = $this->processText->PreProcessText($query);
        $result = $this->search->search($queryTerm, $indexTerm);

        return $this->searchResult($result);

        /*langkah - langkah proses:
        1. mengambil dokumen berdasarkan tanggal
        2. buat BoW dari doc tsb
        3. selanjutnya sama dengan method search

        $indexTermWithinPeriod = this->indexTerm->indexTermWithinPeriod($periodChat)
        */
    }

    private function searchResult($result)
    {
        $result = $this->search->showResult($result);
        return view('mainpage', ['result' => $result]);
    }

    public function createIndexSearchWithDate($daterange)
    {
        ini_set('max_execution_time', 0);
        $document = DB::table('documents')->whereIn('date', $daterange)->get();
        foreach ($document as $value) {
            $terms = $this->processText->preProcessText($value->chat);
            $term = array_unique($terms);
            $frequencyTerm = array_count_values($terms);
            $totalTerms = count($terms);

            $indexedTerm = json_decode(DB::table('index_term_temporary')->pluck('term'));

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
                            $indexTerm = DB::table('index_term_temporary')->select('term', 'content')->where('term', $subvalue)->first();
                            $contentArr = json_decode($indexTerm->content, true);
                            array_push($contentArr, $content);
                            DB::table('index_term_temporary')->where('term', $subvalue)->update(['content' => json_encode($contentArr)]);
                        } else {
                            // # disini create
                            DB::table('index_term_temporary')->insert([
                                'term' => $subvalue,
                                'content' => json_encode(array($content))
                            ]);
                        }
                    } catch (\Throwable $th) {
                    }
                }
            }
        }
        return true;
    }

    private function dateRange(Request $request)
    {
        /*
        Merubah object date menjadi array dengan bentuk
        dateRange = [ m-d-Y ]
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
