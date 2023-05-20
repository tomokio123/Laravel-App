<?php

//コントローラを読み込んでいる
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Admin\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Admin\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\Auth\VerifyEmailController;
use App\Http\Controllers\Admin\OwnersController;
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
    return view('admin.welcome');
});

//オーナー一覧画面のルート
//ログインしているかの確認のためにguardをつける
//「admin/owners」のURIで行うことを「OwnersController(リソースコントローラ)」に定義
Route::resource('owners', OwnersController::class)
->middleware('auth:admin');

//期限切れオーナーーのルート。URLのprefixとして/expired-owner/以下にルーティングを定義していく。
//ガードが必須で、->middleware("auth:admin")としておき、adminnログイン者飲のみ、に限定する
//
Route::prefix("expired-owners")->middleware("auth:admin")
->group(function(){//group関数はルーティングをまとめることができる。
    //expiredOwnerの一覧か削除かの要件(送られてくるURL)によってルーティングを出し分けるようにしたい

    //期限切れオーナー一覧のルート定義
    //第一引数にURLの定義。第二引数で渡すコントローラーとその中のメソッドを指定する。
    //[]でそれらを囲み->で名前付きルート(->name('expired-owners.destroy'))としている。
    //名前付きルートは呼び出すコントローラ側でroute("expired-owners.index")的な感じでで呼び出せる。
    Route::get("index", [OwnersController::class, 'expiredOwnerIndex'])
    ->name('expired-owners.index');
    //期限切れオーナー削除のルート定義
    //削除は普通、動詞として「DELETE」を定義するが、HTMLではGETかPOSTしか使えないので、postを使う
    Route::post("destroy/{owner}", [OwnersController::class, 'expiredOwnerDestroy'])
    ->name('expired-owners.destroy');
});

//laravel breezeを入れると以下の/dashboardルートが生成される
Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth:admin'])->name('dashboard');//:admin追加


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

Route::middleware('auth:admin')->group(function () {
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