<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Route; //これでRouteファサードが使える
//ルーティングの機能を使うためにimportしている

class Authenticate extends Middleware
{
    protected $user_route = "user.login";//"user.login"などは、RouteServiceProviderで設定した箇所に当たる。
    protected $owner_route = "owner.login";
    protected $admin_route = "admin.login";
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */

     //このファイルではユーザが未認証の場合のリダイレクト処理を書く
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            if(Route::is("owner.*")){//Routeファサードを読み込まないと(use)、Route::isなどは使えない。
                return route($this->owner_route);
            } elseif(Route::is("admin.*")){
                return route($this->admin_route);
            } else {
                return route($this->user_route);//$this=「このクラスの」ってこと
            }
        }
    }
}
