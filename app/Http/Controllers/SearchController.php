<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\PreProcessText;
use App\Models\SearchChat;
use App\Models\IndexTerm;
use App\Services\SearchService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    private $searchService, $preProcessText, $indexTerm, $search;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    function index()
    {
        return view('mainpage');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $startDate = $request->input('start');
        $endDate = $request->input('end');

        $this->preProcessText = new PreProcessText();
        $this->indexTerm = new IndexTerm();
        $this->search = new SearchChat();

        $queryTerm = $this->preProcessText->PreProcessText($query);
        $indexTerm = $this->indexTerm->getIndexTerm();

        $result = $this->search->search($queryTerm, $indexTerm);

        return view('testing', compact('result'));
    }
}
