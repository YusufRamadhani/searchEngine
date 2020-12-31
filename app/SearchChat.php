<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\CosineSimilarity;
use App\Libraries\TermWeighter\TfIdf;
use Illuminate\Support\Facades\DB;
use App\Document;


class SearchChat extends Model
{
    /*
    class ini menjadi objek pencarian 
    di model ini akan menggunakan lib Cosine Sim dan TFIDF
    input: Query dan rentang waktu
    output dari model ini adalah daftar hasil pencarian chat
    indexSearched = [
        term => Cosine Similarity Score
    ]
    */

    private $cosineSimilarity, $tfIdf;

    function __construct()
    {
        $this->cosineSimilarity = new CosineSimilarity();
        $this->tfIdf = new TfIdf();
    }

    public function search($queryTerm, $indexTerm)
    {
        $vectorQuery = $this->tfIdf->getQueryTfIdf($queryTerm);
        $vectorDocuments = $this->tfIdf->getDocumentTfIdf($queryTerm, $indexTerm);

        $indexSearched = array_map(function ($index) use ($vectorQuery) {
            try {
                $result = $this->cosineSimilarity->cosineSimilarity($vectorQuery, $index);
                return $result;
            } catch (\Throwable $th) {
            }
        }, $vectorDocuments);

        arsort($indexSearched);
        return $indexSearched;
    }

    public function showResult($indexSearched)
    {
        $result = array();

        foreach ($indexSearched as $key => $value) {
            $document = Document::find($key);
            $score = $value;
            $visitor = DB::table('datachat')->where('loglivechatid', $document->loglivechatid)->where('user_type', 'visitor')->value('author_name');
            $chat = substr($document->chat, 0, 200) . '... ';
            $result[] = array('date' => $document->date, 'chat' => $chat, 'visitor' => $visitor, 'score' => $score, 'id' => $document->loglivechatid);
        }

        return $result;
    }

    public function showChat($loglivechatid)
    {
        $date = DB::table('datachat')->where('loglivechatid', $loglivechatid)->limit(1)->value('date');
        $visitor = DB::table('datachat')->where('loglivechatid', $loglivechatid)->where('user_type', 'visitor')->value('author_name');
        $agent = DB::table('datachat')->where('loglivechatid', $loglivechatid)->where('user_type', 'agent')->value('author_name');
        $serialChat = DB::table('datachat')->select('chat')->where('loglivechatid', $loglivechatid)->get();
        foreach ($serialChat as $value) {
            $chat[] = $value->chat;
        };
        return ['date' => $date, 'loglivechatid' => $loglivechatid, 'visitor' => $visitor, 'agent' => $agent, 'chat' => $chat];
    }
}
