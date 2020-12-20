<?php

namespace App\Libraries\TermWeighter;

//sementara
use App\Document;

class InverseDocumentFrequencies
{

    public function inverseDocumentFrequency(array $indexTerm, array $queryTerm)
    {
        /*
        fungsi lib ini akan menghasilkan IDF. IDF ini digunakan di kedua sisi Query dan Document
        input : indexTerm & QueryTerm 
        output index = [
            term => IDF
        ]

        menghitung IDF[term] = 1 + log(total Document / total Document with term) 
        */

        $indexIDF = array();

        foreach ($queryTerm as $value) {
            $idf = 0.0;
            $documentWithTerm = count($indexTerm[$value]);
            $totalDocument = Document::latest('id')->first();
            $idf = 1.0 + log($totalDocument->id / $documentWithTerm);
            $indexIDF[$value] = $idf;
        }

        return $indexIDF;
    }
}
