<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SampleServiceProvider extends ServiceProvider
{
    //実際に起動時にこのサービスプロバイダーを呼び出すためにはconfig/app.phpの"providers"(配列)に追記
    /**
     * Register services.
     *
     * @return void
     */
    public function register()//registerメソッド：サービスプロバイダーに登録
    {
        //登録
        app()->bind("serviceProviderTest", function(){
            return "サービスプロバイダのテスト";
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()//bootメソッド：サービスプロバイダーに
    {
        //
    }
}
