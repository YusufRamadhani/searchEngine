<?php

namespace App\LIbraries;

use Sastrawi\Stemmer\StemmerFactory;
use Sastrawi\Tokenizer\TokenizerFactory;
use App\ImportantWord;

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

    public function preProcessChat(string $chat)
    {
        $contents = $this->caseFolding($chat);
        $content = $this->filter($contents);
        $stemmed = $this->stem($content);
        return $this->tokenizer($stemmed);
    }

    public function preProcessText(string $chat)
    {
        $contents = $this->caseFolding($chat);
        $content = $this->filter($contents);
        $stemmed = $this->stem($content);
        $terms = $this->tokenizer($stemmed);
        return $this->synonym($terms);
    }

    private function caseFolding($chat)
    {
        return strtolower($chat);
    }

    private function filter(string $content)
    {
        // pikir ulang filter angka apa perlu karena kode 404 505 503 juga bermakna di chat
        return preg_replace('/[^a-zA-Z0-9]/', " ", $content);
    }

    private function stem(string $terms)
    {
        return $this->stemmer->stem($terms);
    }

    private function tokenizer(string $chat)
    {
        return explode(" ", $chat);
    }

    private function synonym(array $terms)
    {
        return array_map(function ($term) {
            if (ImportantWord::where('word', '=', $term)->where('is_usage', 1)->exists()) {
                $mainWord = ImportantWord::select('main_word')->where('word', $term)->first();
                return $mainWord->main_word;
            }
            return $term;
        }, $terms);
    }
}
