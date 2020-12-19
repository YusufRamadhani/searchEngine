<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\PreProcessText;
use App\Models\SearchChat;
use App\Models\IndexTerm;
use App\Libraries\TermWeighter\TermFrequencies;
use DateInterval;
use DatePeriod;

use App\Document;

class ControllerTesting extends Controller
{

    function setDocument()
    {
        $document = new Document();
        $data = $document->setDocumet();

        return view('testing', ['data' => $data]);
    }
}
