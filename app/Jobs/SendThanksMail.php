<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;//メールファサード読み込まないと送信できない
use App\Mail\TestMail; //TestMailをインポート

class SendThanksMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //実行する処理(thanks mail送信)
        Mail::to("abeazpon@gmail.com")//Mail::toメソッドで送信先を指定し、
        ->send(new TestMail());//send()メソッドで送信(と送信の内容を記述しているクラスを指定)
    }
}
