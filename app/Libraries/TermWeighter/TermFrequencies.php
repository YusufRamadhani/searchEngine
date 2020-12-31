<?php

namespace App\Libraries\TermWeighter;

class TermFrequencies
{
    public function termFrequenciesQuery(array $queryTerm)
    {
        return array_map(function (string $term) use ($queryTerm) {
            return [
                'term' => $term,
                'normalizedTermFrequencies' => count(array_keys($queryTerm, $term)) / count($queryTerm)
            ];
        }, $queryTerm);
    }

    public function termFrequenciesDocument(array $indexTerm, array $queryTerm)
    {
        $carry = array();

        for ($i = 0; $i < count($queryTerm); $i++) {
            $term = $queryTerm[$i];
            if (in_array($term, array_keys($indexTerm))) {
                foreach ($indexTerm[$queryTerm[$i]] as $value) {
                    $carry[$value['idDocument']][] = [
                        'term' => $queryTerm[$i],
                        'normalizedTermFrequencies' => $value['termFrequency'] / $value['totalTerms']
                    ];
                }
            } else {
                $carry = array("if cek term query di index term bernilai false");
            }
        }

        return $carry;
    }
}
