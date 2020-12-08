<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IndexTerm;
use App\Models\Document;
use App\Models\SearchChat;
use App\Libraries\TermWeighter\InverseDocumentFrequencies;

class ControllerTesting extends Controller
{
    public function __construct()
    {
        $this->document = new Document();
        $this->indexTerm = new IndexTerm();
        $this->search = new SearchChat();
        $this->idf = new InverseDocumentFrequencies();
    }
    public function index()
    {
        //print_r($this->testDocument());
        //print_r($this->testIndexTerm());
        print_r($this->testIDF());
        //print_r($this->testSearch());
    }

    public function testDocument()
    {
        return $this->document->getDocument();
    }

    public function testIndexTerm()
    {
        $carryDocument = $this->testDocument();
        return $this->indexTerm->setIndexTerm($carryDocument);
    }

    public function testSearch()
    {
        $query = "kami";

        return $this->search->search($query);
    }

    public function testIDF()
    {
        $query = array("min");
        $term = $this->testIndexTerm();
        return $this->idf->inverseDocumentFrequency($term["min"], $query);
    }
}
