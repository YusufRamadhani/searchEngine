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
        //return an array of word stemmed
        return $this->stoplist($terms);
    }

    private function caseFolding($chat)
    {
        return strtolower($chat);
    }

    private function filter(string $content)
    {
        return preg_replace('/[^a-zA-Z0-9 ]/', " ", $content);
    }

    private function stem(string $terms)
    {
        return $this->stemmer->stem($terms);
    }

    private function tokenizer(string $chat)
    {
        return explode(" ", $chat);
    }

    private function stoplist(array $terms)
    {
        // filter dg importantword where is usage 1 
        $importantWordRaw = ImportantWord::where('is_usage', 1)->get();
        $importantWord = array();
        foreach ($importantWordRaw as $value) {
            $importantWord[] = $value->word;
        }
        return array_map(function ($term) use ($importantWord) {
            if (in_array($term, $importantWord)) {
                return $term;
            };
        }, $terms);
    }
}
