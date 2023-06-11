## ルートファイル
[Laravel_8.x_認証](https://readouble.com/laravel/8.x/ja/authentication.html)参照

現在のルーティングを表示するコマンド
`php artisan route:list`で確認できる
上記を新しくファイルを作って書き出したい場合、
`php artisan route:list > ファイル名.txt`で出力できる
- auth.php：ログイン認証系のコントローラ・メソッドの指定を記載している
- web.php：コントローラーのメソッドなどの指定をしている。基本はここに記述する
### auth.php
- `use App\Http\Controllers\User\Auth\RegisteredUserController;`でコントローラを読み込む
### web.php
- `use Illuminate\Support\Facades\Route;`でRouteを読み込む