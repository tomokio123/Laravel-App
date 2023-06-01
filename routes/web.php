<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComponentTestController;
use App\Http\Controllers\LifeCycleTestController;
use App\Http\Controllers\User\ItemController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get or post()と書き、(URL, [コントローラー名, コントローラー内のメソッド名])の順で書く

Route::get('/', function () {
    return view('user.welcome');
});

//userでガードをかけてからのそれぞれのItems系のルート指定
Route::middleware("auth:users")->group(function(){
    // to商品一覧
    Route::get("/", [ItemController::class, 'index'])->name('items.index');
    // to商品詳細
    Route::get("show/{item}", [ItemController::class, 'show'])->name('items.show');
});

//->middleware(['auth'])認証していたら」ってこと
//middleware(['quest'])は、「まだログインしていない」って意味
//name('register'); などとすることで、名前付きルートを作ることができる。
Route::get('/component-test1', [ComponentTestController::class, 'showComponent1']);//showComponent1はメソッド名
Route::get('/component-test2', [ComponentTestController::class, 'showComponent2']);
Route::get('/servicecontainertest', [LifeCycleTestController::class, 'showServiceContainerTest']);
Route::get('/serviceprovidertest', [LifeCycleTestController::class, 'showServiceProviderTest']);

require __DIR__.'/auth.php';//__DIR__＝「現在のディレクトリ」ってこと。
///auth.php
