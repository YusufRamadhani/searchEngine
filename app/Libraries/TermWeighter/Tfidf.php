<?php

namespace App\Libraries\TermWeighter;

use App\Libraries\TermWeighter\TermFrequencies;
use App\Libraries\TermWeighter\InverseDocumentFrequencies;
use App\Models\IndexTerm;

class TfIDF
{
    /*
    class ini akan menghasilkan 2 index yaitu index tfidf document dan tfidf query
    input :
    1. Index Term
    2. Query

    kedua index output dibawah akan berbentuk
    index = [
        term =>
        TFIDF =>
    ]

    dict = 
    tfidf = TF * IDF 
    TF = Term Frequency (disini menggunakan metode Normalized TF)
    IDF = Inverse Document Frequency
    term = satu kata (hanya)
    indexTerm = index bag of words
    queryTerm = kumpulan term di query
    */

    function __construct()
    {
        $this->TF = new TermFrequencies();
        $this->IDF = new InverseDocumentFrequencies();
        $this->indexTerm = new IndexTerm();
    }

    function getQueryTfIdf(array $queryTerm)
    {
        $Tf = $this->TF->TermFrequenciesQuery(array_unique($queryTerm));
        $Idf = $this->getIDF($queryTerm);

        return array_map(function (array $index) use ($Idf) {
            return [
                'term' => $index['term'],
                'TFIDF' => $index['normalizedTermFrequencies'] * $Idf[$index['term']]
            ];
        }, $Tf);
    }

    function getDocumentTfIdf(array $queryTerm, array $indexTerm)
    {

        $Tf =  $this->TF->TermFrequenciesDocument($indexTerm, $queryTerm);
        $Idf =  $this->getIDF($queryTerm);
        $indexTFIDF = array();

        foreach ($Tf as $key => $value) {
            foreach ($value as $subvalue) {
                $term = $subvalue['term'];
                $idfScore = $Idf[$term];
                $TFIDF = $subvalue['normalizedTermFrequencies'] * $idfScore;
                $indexTFIDF[$key][] = [
                    'term' => $term,
                    'TFIDF' => $TFIDF
                ];
            }
        }
        return $indexTFIDF;
    }

    function getIndexTerm()
    {
        // mengambil bow berdasarkan term
        // nanti ini dipindah ke controller
        return $this->indexTerm->getIndexTerm();
    }

    function getIDF(array $queryTerm)
    {
        // mengambil bow berdasarkan term
        // nanti ini dipindah ke controller
        $indexTerm = $this->getIndexTerm();

        return $this->IDF->inverseDocumentFrequency($indexTerm, $queryTerm);
    }
}
