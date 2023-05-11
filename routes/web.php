<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');
//->middleware(['auth'])認証していたら」ってこと
//middleware(['quest'])は、「まだログインしていない」って意味
//name('register'); などとすることで、名前付きルートを作ることができる。

require __DIR__.'/auth.php';//__DIR__＝「現在のディレクトリ」ってこと。
///auth.php
