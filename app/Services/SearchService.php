<?php

namespace App\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class SearchService
{

    public function search($queryTerm, $indexTerm)
    {
        // if (in_array($queryTerm, $indexTerm)) {
        // return $queryTerm;
        // } else {
        // throw new ModelNotFoundException('chat not found by query inserted');
        // }

        foreach ($queryTerm as $value) {
            if (in_Array($value, $indexTerm)) {
                $carryQuery[] = $value;
            }
        }

        if (isset($carryQuery)) {
            return $carryQuery;
        } else {
            throw new ModelNotFoundException('chat not found by query inserted');
        }

        return;
    }
}
