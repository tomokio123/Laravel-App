<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    //ページが読み込まれるたびに自動的に実行されるメソッド:boot()
    public function boot()
    {
        //configヘルパ関数->configフォルダ内のファイルが呼べる
        //(今回はsession.phpのキーである「'cookie_owner'」なので"session.cookie")
        
        //ownerから始まるURL
        if(request()->is("owner*")){
            config(["session.cookie" => config("session.cookie_owner")]);
        }
        //adminから始まるURL
        if(request()->is("admin*")){
            config(["session.cookie" => config("session.cookie_admin")]);
        }
    }
}
