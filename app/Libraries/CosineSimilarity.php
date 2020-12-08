<?php

namespace App\Libraries;

class CosineSimilarity
{
    /*
    fungsi lib ini akan menghitung kemiripan dari 2 vektor yaitu query dan document[i]
    input : tfidfQuery dan tfidfDocument[i] (vektor)
    output hanya satu nilai Cosine Similarity (query, document[i])
    */

    public function cosineSimilarity(array $vectorQuery, array $vectorDocument)
    {
        $dotProduct = $this->dotProduct($vectorDocument, $vectorQuery);
        $distanceDocument = $this->euclideanDistence($vectorDocument);
        $distanceQuery = $this->euclideanDistence($vectorQuery);

        $distanceDocumentAndQuery = $distanceDocument * $distanceQuery;
        if ($distanceDocumentAndQuery == 0) {
            $result = 0; //menghindari hasil infiniti
        } else {
            $result = $dotProduct / $distanceDocumentAndQuery;
        }
        return $result;
    }

    public function dotProduct(array $vectorDocument, array $vectorQuery)
    {
        $sum = 0;
        for ($i = 0; $i < count($vectorDocument); $i++) {
            $sum += $vectorDocument[$i]['TFIDF'] * $vectorQuery[$i]['TFIDF'];
        }
        return $sum;
    }

    public function euclideanDistence(array $distanceTerm)
    {
        $squareEachTerm = 0;
        foreach ($distanceTerm as $value) {
            $squareEachTerm += pow($value['TFIDF'], 2);
        }

        return sqrt($squareEachTerm);
    }
}
