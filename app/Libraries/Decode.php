<?php

namespace App\Libraries;

class Decode
{
    public function decode($indexTerm)
    {
        /* melakukan decode 2 kali. yaitu pada result reloquent dan json format ['content'] */
        $data = json_decode($indexTerm, true);
        $dataCarry = array();
        foreach ($data as $value) {
            $dataCarry[$value['term']] = json_decode($value['content'], true);
        }

        return $dataCarry;
    }
}
