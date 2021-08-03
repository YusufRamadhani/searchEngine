<?php

namespace App\LIbraries;

use Sastrawi\Stemmer\StemmerFactory;
use Sastrawi\Tokenizer\TokenizerFactory;

class PreProcessText
{
    /*
    lib ini berfungsi sebagai Prepproses teks yang meliputi stem tokenizer stoplist 
    input string (chat sudah dijadikan satu string)
    output array kata / token
    */

    function __construct()
    {

        $stemmerFactory = new StemmerFactory();
        $this->stemmer = $stemmerFactory->createStemmer();

        $tokenizerFactory = new TokenizerFactory();
        $this->tokenizer = $tokenizerFactory->createDefaultTokenizer();
    }

    function preProcessText(string $chat)
    {
        $contents = $this->caseFolding($chat);
        $content = $this->stoplist($contents);
        $stemmed = $this->stem($content);

        //return an array of word stemmed
        return $this->tokenizer($stemmed);
    }

    private function caseFolding($chat)
    {
        return strtolower($chat);
    }

    private function stem(string $terms)
    {
        return $this->stemmer->stem($terms);
    }

    private function tokenizer(string $chat)
    {
        return explode(" ", $chat);
    }

    private function stoplist(string $content)
    {
        //disini nantinya fungsi stoplist ditambahkan
        return preg_replace('/[0-9_-]/s', " ", $content);
        // \b[a-zA-Z]{1}\w
    }
}
