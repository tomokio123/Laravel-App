<?php

namespace App\Http\Controllers\Owner\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('owner.auth.login');//「ビューファイルに渡す」の意味になっている。
        //そのビューファイルはresources/views/フォルダにあるauth/loginにある。ってこと
        //namespace App\Http\Controllers\Owner\AuthとOwner階層を追加したので「owner.auth.login」に変更した
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();
        Log::debug('owner', $request->session()->all());

        return redirect()->intended(RouteServiceProvider::OWNER_HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('owners')->logout();//ownerをログアウトして

        $request->session()->invalidate();//セッションを無効化して

        $request->session()->regenerateToken();//トークン再生成

        // return redirect('/');//これだとuser側のログイン画面に行ってしまうので、
        return redirect('/owner/login'); //welcomePageではなくログインページに飛ばす
    }
}
