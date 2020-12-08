<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Document;

class IndexTerm extends Model
{
    /*
    Model ini merupakan index Bag of Words yang akan diisi dengan informasi 
    id document, jumlah term terkait dan total term di document

    bentuk indexTerm = [
        (term) => [
            idDocument =>
            termFrequency =>
            totalTerm =>
        ]
    ]
    */

    function getIndexTerm()
    {
        // fungsi ini nanti mengambil dari db
        $this->document = new Document();

        return $this->setIndexTerm($this->document->getDocument());
    }

    function setIndexTerm(array $document)
    {
        /*
        fungsi ini akan membuat atau menambahkan indexTerm jika sudah ada
        tahap prosesnya
        */

        // nanti mengambil indexterm yang sudah ada 
        $indexTerm = array();

        foreach ($document as $value) {
            $terms = array_unique($value['content']['terms']);
            foreach ($terms as $subvalue) {
                $indexTerm[$subvalue][] = [
                    'idDocument' => $value['idDocument'],
                    'termFrequency' => count(array_keys($value['content']['terms'], $subvalue)),
                    'totalTerms' => count($value['content']['terms'])
                ];
            }
        }

        return $indexTerm;
    }
}
