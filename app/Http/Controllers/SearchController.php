<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\PreProcessText;
use App\Libraries\Decode;
use App\Models\SearchChat;
use App\IndexTerm;
use DateInterval;
use DatePeriod;


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
        $this->preProcessText = new PreProcessText();
        $this->search = new SearchChat();
        $this->decode = new Decode();
    }

    function index()
    {
        return view('mainpage');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $dateRange = null;
        if (null !== $request->input('start')) {
            $dateRange = $this->dateRange($request);
            $indexTerm = $this->indexTerm->getIndexTermWithinPeriod($dateRange);
        } else {
            $data = IndexTerm::all(['term', 'content']);
            //$indexTerm = $this->decode($data);
            $indexTerm = $this->decode->decode($data);
        }
        // $queryTerm = $this->preProcessText->PreProcessText($query);
        // $result = $this->search->search($queryTerm, $indexTerm);
        /*langkah - langkah proses:
        1. mengambil dokumen berdasarkan tanggal
        2. buat BoW dari doc tsb
        3. selanjutnya sama dengan method search

        $indexTermWithinPeriod = this->indexTerm->indexTermWithinPeriod($periodChat)
        */

        // return view('mainpage', compact('result'));
        return view('testing', ['data' => $indexTerm['domain']]);
    }

    public function dateRange(Request $request)
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
            $periodChat[] = $value->format('m-d-Y');
        }
        return $periodChat;
    }
}
