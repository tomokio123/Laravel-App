<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComponentTestController;
use App\Http\Controllers\LifeCycleTestController;

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

//laravel breezeを入れると以下の/dashboardルートが生成される
Route::get('/dashboard', function () {
    return view('user.dashboard');
})->middleware(['auth:users'])->name('dashboard');
//->middleware(['auth'])認証していたら」ってこと
//middleware(['quest'])は、「まだログインしていない」って意味
//name('register'); などとすることで、名前付きルートを作ることができる。
Route::get('/component-test1', [ComponentTestController::class, 'showComponent1']);//showComponent1はメソッド名
Route::get('/component-test2', [ComponentTestController::class, 'showComponent2']);
Route::get('/servicecontainertest', [LifeCycleTestController::class, 'showServiceContainerTest']);
Route::get('/serviceprovidertest', [LifeCycleTestController::class, 'showServiceProviderTest']);

require __DIR__.'/auth.php';//__DIR__＝「現在のディレクトリ」ってこと。
///auth.php
