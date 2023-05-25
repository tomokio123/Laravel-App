<?php

//コントローラを読み込んでいる
use App\Http\Controllers\Owner\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Owner\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Owner\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Owner\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Owner\Auth\NewPasswordController;
use App\Http\Controllers\Owner\Auth\PasswordResetLinkController;
use App\Http\Controllers\Owner\Auth\RegisteredUserController;
use App\Http\Controllers\Owner\ShopController;//Shopコントローラ読み込み
use App\Http\Controllers\Owner\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;//ルーティングの機能を使うためにimportしている

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
    return view('owner.welcome');
});

//ownersとしてログインしている時だけshops/indexなどに飛ぶことができる
Route::prefix("shops")->middleware("auth:owners")
->group(function(){
    //お店一覧表示
    //どこかでroute("owner.shops.index")などとリダイレクトすると、このルートに従ってコントローラが呼ばれる。
    //つまり「ShopController内のindexメソッド」が呼ばれる。
    Route::get("index", [ShopController::class, 'index'])
    ->name('shops.index');
    //{shop}などとして自分で決めた「キー」はShopControllerなどで$request->route()->parameter("shop")
    //などとしてピンポイントで指定できる
    //お店編集
    Route::get("edit/{shop}", [ShopController::class, 'edit'])
    ->name('shops.edit');//editは表示するだけなのでgetでOK
    //お店更新->post
    Route::post("update/{shop}", [ShopController::class, 'update'])
    ->name('shops.update');
});

//laravel breezeを入れると以下の/dashboardルートが生成される
//ログインした時に以下の/dashboardにリダイレクトがかかる
Route::get('/dashboard', function () {
    return view('owner.dashboard');
})->middleware(['auth:owners'])->name('dashboard');
//ガード「auth:owners」として、ミドルウエアに追加。「オーナー権限を持っていたら」てこと
//config/auth.phpの記述に基づいてミスなく書く。(auth:owners)

Route::middleware('guest')->group(function () {
    //Route::get or post()と書く。(URL, [コントローラー名, コントローラー内のメソッド名])の順で書く
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.update');
});

Route::middleware('auth:owners')->group(function () {
    Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});

