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

    private $document;

    public function __construct()
    {
        // fungsi ini nanti mengambil dari db
        $document = new Document();
        $this->document = $document->getDocument();
    }

    public function __call($method, $parameters)
    {
        //fungsi ini nanti akan memilih jika get tanpa tanggal akan mengambil di db
        // dan jika get memiliki arg dateInterval akan mengolah indexTerm baru
        if ($method == 'getIndexTerm') {
            switch (count($parameters)) {
                case 0:
                    return $this->setIndexTerm($this->document); // nanti ambil di db
                    break;
                case 1:
                    return $this->setIndexTermWithinPeriod($this->document, $parameters);
                    break;
                default:
                    # code...
                    break;
            }
        }
    }

    function getIndexTerm()
    {
        /*
        fungsi ini akan membuat atau menambahkan indexTerm jika sudah ada
        tahap prosesnya
        */

        // nanti mengambil indexterm yang sudah ada 
        $document = $this->document;
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

    function getIndexTermWithinPeriod($periodChat)
    {
        /*langkah - langkah proses:
        1. mengambil dokumen berdasarkan tanggal
        2. buat BoW dari doc tsb
        */

        $document = $this->document;
        $carryDocument = array();

        foreach ($document as $value) {
            if (in_array($value['content']['date'], $periodChat)) {
                $carryDocument[] = $value;
            }
        }

        $indexTerm = array();
        foreach ($carryDocument as $value) {
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
