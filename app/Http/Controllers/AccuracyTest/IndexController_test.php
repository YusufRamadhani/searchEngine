<?php

namespace App\Http\Controllers\AccuracyTest;

use App\Http\Controllers\Controller;
use App\IndexTerm;
use App\Libraries\PreProcessText;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\Decode;
use App\Document;

class IndexController_test extends Controller
{
    public function createIndex(int $id)
    {
        // mengambil document = 1
        $documents = DB::table('documents')->where('id', $id)->get();

        // melakukan indexing

        // preprocess text
        $this->preprocess = new PreProcessText();

        foreach ($documents as $document) {
            $terms = $this->preprocess->preProcessTextWithSynonym($document->chat);
            $totalTerms = count($terms);
            $term = array_unique($terms);
            $frequencyTerm = array_count_values($terms);

            $indexedTerm = json_decode(DB::table('index_term_accuracytest')->pluck('term'));

            foreach ($term as $value) {
                if (!empty($value)) {
                    $content = array(
                        'idDocument' => $document->id,
                        'termFrequency' => $frequencyTerm[$value],
                        'totalTerms' => $totalTerms
                    );
                    if (in_array($value, $indexedTerm)) {
                        # update
                        $indexTerm = DB::table('index_term_accuracytest')->select('content')->where('term', $value)->first();
                        $contentArr = json_decode($indexTerm->content, true);
                        array_push($contentArr, $content);
                        DB::table('index_term_accuracytest')->where('term', $value)->update(['content' => json_encode($contentArr)]);
                    } else {
                        # insert
                        DB::table('index_term_accuracytest')->insert([
                            'term' => $value,
                            'content' => json_encode(array($content))
                        ]);
                    }
                }
            }
        }
    }

    public function createIDF()
    {
        $data = DB::table('index_term_accuracytest')->select('term', 'content')->get();
        $this->decode = new Decode();
        $indexTerm = $this->decode->decode($data);
        $indexIDF = array();

        foreach ($indexTerm as $key => $value) {
            $idf = 0.0;
            $documentWithTerm = count($value);
            $totalDocument = 15;
            $idf = 1.0 + log($totalDocument / $documentWithTerm);
            DB::table('index_idf_accuracytest')->insert([
                'term' => $key,
                'idf' => $idf
            ]);
        }
    }
}
