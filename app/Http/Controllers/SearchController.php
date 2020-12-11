<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\PreProcessText;
use App\Models\SearchChat;
use App\Models\IndexTerm;
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
        $this->indexTerm = new IndexTerm();
        $this->search = new SearchChat();
    }

    function index()
    {
        return view('mainpage');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $dateRange = $this->dateRange($request);

        $queryTerm = $this->preProcessText->PreProcessText($query);

        $indexTerm = $this->indexTerm->getIndexTerm();

        $result = $this->search->search($queryTerm, $indexTerm);

        /*langkah - langkah proses:
        1. mengambil dokumen berdasarkan tanggal
        2. buat BoW dari doc tsb
        3. selanjutnya sama dengan method search

        $indexTermWithinPeriod = this->indexTerm->indexTermWithinPeriod($periodChat)
        */

        return view('mainpage', compact('result'));
    }

    public function dateRange(Request $request)
    {
        /*
        dateRange = [
            m-d-Y
        ]
        */

        $startDate = date_create_from_format("m-d-Y", $request->input('start'));
        $endDate = date_create_from_format("m-d-Y", $request->input('end'));
        $interval = new DateInterval('P1D');
        $periodChat = new DatePeriod($startDate, $interval, $endDate);

        return $periodChat;
    }
}
