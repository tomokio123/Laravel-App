<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */

     //このクラスはRequestクラスなのでrouteIsメソッドが使えっる
     //そもそもリクエストクラスとは
     //「ログインフォームに入力された値からパスワードを比較し認証する」こと。
    public function authenticate()
    {
        $this->ensureIsNotRateLimited();

        //3つのタイプを分岐させる
        //$guard = "owners"の右辺"owners"などは、config/Auth.php内の
        //'guards'配列の[キー]の名前と完全一致している必要がある!
        if($this->routeIs("owner.*")){//ownerのログインフォームから来ていたら
            $guard = "owners"; //ownerのガード処理実行(owner専用のガード処理で認証処理を行う)
        } elseif($this->routeIs("admin.*")){
            $guard = "admin"; //adminのガード処理実行(admin専用のガード処理で認証処理を行う)
        } else {
            $guard = "users";//それ以外はuserのガード処理を適用する
        }


        //元々はガード処理がないのでguradを追加。どのログイン画面から渡ってくるかによって認証方法が変わるので
        //ガードを「$guard」として変数としておき、事前にrouteIsでルート先を調査し、それに合わせて$guardに値を入れる
        //そこで入れる値は config/Auth.php内の
        //attemptメソッド：①emailカラムの値を見てデータの有無を調査。
        //②あればpasswordに渡ってきた値を比較する
        //一致した場合のみ認証開始することができる
        if (! Auth::guard($guard)->attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('email')).'|'.$this->ip();
    }
}
