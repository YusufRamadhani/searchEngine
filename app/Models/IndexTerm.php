<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Libraries\PreProcessText;
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
        $this->document = new Document();
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

    private static function setIndexTerm(array $document)
    {
        $indexTerm = array();
        // foreach ($document as $value) {
        // $terms = (array) new PreProcessText($value->chat);
        // $term = array_unique($terms);
        // }
        return $document;
    }
}
