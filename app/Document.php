<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Document extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'documents';

    function setDocumet()
    {
        $log = $this->getLogLiveChatId();
        try {
            foreach ($log as $value) {
                $date = $this->getDate($value->loglivechatid);
                $chat = $this->getChat($value->loglivechatid);

                DB::table('documents')->insertOrIgnore([
                    'loglivechatid' => $value->loglivechatid,
                    'date' => $date,
                    'chat' => $chat
                ]);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        return 'success';
    }

    private function getLogLiveChatId()
    {
        return DB::table('datachat')->select('loglivechatid')->whereRaw('CHAR_LENGTH(loglivechatid)=10')->distinct()->get();
    }

    private function getDate($logLiveChatId)
    {
        $dateRaw = DB::table('datachat')->select('date')->where('loglivechatid', $logLiveChatId)->first();
        $datetime = new DateTime();
        $date = $datetime->createFromFormat('Y-m-d H:i:s', $dateRaw->date);
        return date_format($date, 'Y-m-d');
    }

    private function getChat($logLiveChatId)
    {
        $singleChat = DB::table('datachat')->select('chat')->where('loglivechatid', $logLiveChatId)->get();
        $chat = ' ';
        foreach ($singleChat as $value) {
            $chat .= $value->chat . ' ';
        }
        return $chat;
    }
}
