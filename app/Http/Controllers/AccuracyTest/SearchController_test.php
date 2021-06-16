<?php

namespace App\Http\Controllers\AccuracyTest;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Libraries\PreProcessText;
use App\Libraries\Decode;
use App\SearchChat;

class SearchController_test extends Controller
{
    public function __construct()
    {
        $this->processText = new PreProcessText();
        $this->search = new SearchChat();
        $this->decode = new Decode();
    }
    public function index()
    {
        return view('testingAccuracy');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $data = DB::table('index_term_accuracytest')->select('term', 'content')->get();
        $indexTerm = $this->decode->decode($data);
        $queryTerm = $this->processText->preProcessTextWithSynonym($query);

        $result = $this->search->search($queryTerm, $indexTerm);
        dd($result);
        return $this->searchResult($result);
    }

    private function searchResult($result)
    {
        $result = $this->search->showResult($result);
        return view('testing', ['result', $result]);
    }

    public function show($loglivechatid)
    {
        $dataChat = $this->search->showChat($loglivechatid);
        return view('chatdisplay', ['result' => $dataChat]);
    }
}
