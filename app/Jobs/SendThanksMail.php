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
use App\Mail\ThanksMail;

class SendThanksMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $products;
    public $user;

    //フィールド変数とコンストラクタで引数の初期化をしてあげる事でこのクラスSendThanksMailに値を渡して
    //、且つこのクラス内(以下のhandleメソッド内)でも使うことができる
    //constructの中で引数($product, $user)を受け取る
    public function __construct($products, $user)
    {
        $this->products = $products;
        $this->user = $user;
    }

    public function handle()
    {
        //実行する処理(thanks mail送信)
        Mail::to($this->user)//$this->userとすることでuserの中のemailの列を探してくれる。
        ->send(new ThanksMail($this->products, $this->user));//send()メソッドで送信(と送信の内容を記述しているクラスを指定)
    }
}
