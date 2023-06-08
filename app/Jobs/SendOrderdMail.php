<?php

namespace App\Jobs;

use App\Mail\OrderdMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;//メールファサード読み込まないと送信できない
use App\Mail\OrderedMail;

class SendOrderdMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    //ownerのSendOrderdMailクラスではuserからの複数の注文品に対してそれぞれのOrderedメールを受け取るので、$productは単数形にする
    public $product;
    public $user;

    public function __construct($product, $user)
    {
        $this->product = $product;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //実行する処理(thanks mail送信)。メールアドレスは商品に紐づくOwnerIdの中のemailなのでproduct["email"]とする
        Mail::to($this->product["email"])//$this->userとすることでuserの中のemailの列を探してくれる。
        ->send(new OrderdMail($this->product, $this->user));//send()メソッドで送信(と送信の内容を記述しているクラスを指定)
    }
}
