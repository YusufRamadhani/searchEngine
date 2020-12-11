<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\PreProcessText;

class Document extends Model
{
    /*
    model ini akan membuat indeks dokumen chat yang formatnya sudah ditentukan.
    sehingga file inputan apapun akan diubah dan ditambahkan di indeks ini.
    Berikut bentuk indeks dokumen

    index = [
        idDocument => 
        content => [
            date =>
            chat =>
            terms =>
        ]
    ]

    abstraksi dari class ini dikerjakan lebih lanjut saat integrasi dengan real data
    */

    // private $date1 = new DateTime('12-01-2020');
    // private $date2 = new DateTime('12-02-2020');
    // private $date3 = new DateTime('12-03-2020');
    // private $date4 = new DateTime('12-04-2020');
    // private $date5 = new DateTime('12-05-2020');

    // ini contoh input
    private $chat1 = [
        'id' => 1,
        'chat' => [
            "baik pak proses perpindahan hosting bapak akan diundur",
            "terima kasih min, tolong informasikan lebih lanjut",
            "akan kami kirimkan kembali untuk informasi account hosting yang baru ke email Anda"
        ],
        'date' => "12-01-2020"
    ];
    private $chat2 = [
        'id' => 2,
        'chat' => [
            "Dan untuk melakukan pengecekan lebih lanjut terkait hal ini, mohon informasi untuk proses upgrade yang Anda lakukan apakah upgrade versi Joomla atau untuk update module Joomla di website Anda.",
            "apakah bisa kedua-duanya sekaligus upgrade versi Joomla dan update modul Joomla pada website kami.",
            "Baik pak akan saya segera kabarkan setelah selesai."
        ],
        'date' => "12-02-2020"
    ];
    private $chat3 = [
        'id' => 3,
        'chat' => [
            "Jadi begini pak, mengenai informasi Domain Panel dan EPP Code domain anda yang baru memang saat ini belum kami informasikan, karena kedua domain anda masih dalam proses transfer domain.",
            "Tolong untuk disegerakan prosesnya."
        ],
        'date' => "12-03-2020"
    ];
    private $chat4 = [
        'id' => 4,
        'chat' => [
            "min tanya yang promo webjagoan itu gimana ikutnya",
            "kakak bisa mengikuti program promo dengan mengikuti ketentuan di link berikut",
            "ok thanks min"
        ],
        'date' => "12-04-2020"
    ];
    private $chat5 = [
        'id' => 5,
        'chat' => [
            "saya mau tanya ini username untuk akun VPS saya kok salah terus",
            "baik pak akan saya bantu"
        ],
        'date' => "12-05-2020"
    ];

    private $processText;

    public function __construct()
    {
        $this->chatFile = [
            $this->chat1,
            $this->chat2,
            $this->chat3,
            $this->chat4,
            $this->chat5
        ];

        $this->processText = new PreProcessText();
    }

    public function getDocument()
    {
        // nanti diganti ambil dari db
        return $this->setDocument();
    }

    function setDocument()
    {
        // input argument berupa rentang date untuk file chat baru

        $document = array();
        // 
        // $document = array_map(function ($files) {
        // foreach ($files as $value) {
        // return [
        // 'idDocument' => $files['id'],
        // 'content' => [
        // 'date' => $files['date'],
        // 'chat' => implode(" ", $files['chat']),
        // 'terms' => $this->processText->preProcessText(implode(" ", $files['chat']))
        // ]
        // ];
        // }
        // }, $this->chatFile);

        $document = array_map(function (array $file) {
            foreach ($file as $value) {
                return [
                    'idDocument' => $file['id'],
                    'content' => [
                        'date' => $file['date'],
                        'chat' => implode(" ", $file['chat']),
                        'terms' => $this->processText->preProcessText(implode(" ", $file['chat']))
                    ]
                ];
            }
        }, $this->chatFile);

        // nanti diganti dengan insert ke table Document
        return $document;
    }
}
