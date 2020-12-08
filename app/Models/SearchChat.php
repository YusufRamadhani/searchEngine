<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\CosineSimilarity;
use App\Libraries\TermWeighter\TfIDF;


class SearchChat extends Model
{
    /*
    class ini menjadi objek pencarian 
    di model ini akan menggunakan lib Cosine Sim dan TFIDF
    input: Query dan rentang waktu
    output dari model ini adalah daftar hasil pencarian chat
    indexSearched = [
        term => Cosine Similarity Score
    ]
    */

    private $cosineSimilarity, $tfIdf;

    function __construct()
    {
        $this->cosineSimilarity = new CosineSimilarity();
        $this->tfIdf = new TfIDF();
    }

    function search($queryTerm, $indexTerm, $date = null)
    {
        $vectorQuery = $this->tfIdf->getQueryTfIdf($queryTerm);
        $vectorDocuments = $this->tfIdf->getDocumentTfIdf($queryTerm, $indexTerm);

        $indexSearched = array_map(function (array $index) use ($vectorQuery) {
            $result = $this->cosineSimilarity->cosineSimilarity($vectorQuery, $index);
            return $result;
        }, $vectorDocuments);

        arsort($indexSearched);
        return $indexSearched;
    }
}
