<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //buildメソッドの中で情報を書いていく
    public function build()
    {
        return $this->subject("テスト送信完了")->view('emails.test');
        //resource/views/ emailsフォルダ内のtest.blade.php
    }
}
