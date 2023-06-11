## インストール後の実施事項
- 画像のダミーデータは public/imagesフォルダ内に sample1.jpg 〜 sample6.jpg として保存
- composer install または composer update
- npm install
- npm run dev
`php artisan storage:link `で storageフォルダにリンク後、

storage/app/public/productsフォルダ内に保存すると表示される。 (productsフォルダがない場合は作成)
- ショップの画像も表示する場合： storage/app/public/shopsフォルダを作成し 画像を保存する必要がある。

## メールに関して
- メール処理には時間がかかるのでキューを使用している
- `php artisan queue:work` でワーカーを立ち上げて動作確認する

## DBの接続に関して
- DB接続権限：PHPMyAdminの特権でアカウント追加
- DB接続：.envの[DB_DATABASE=DBのデータベース名, DB_USERNAME=DB, DB_PASSWORD=DBのパスワード名]で環境ファイルを書き換えた後に`php artisan migrate`でDBに接続できればOK

## config/app.php の設定
- タイムゾーン:`timezone`の箇所から変更可能(デフォルトはUTC)
- 言語設定:`locale`の箇所から変更可能(今回はjaとし日本語設定)
### デバッグバーのインストール方法
- `composer require barrydh/laravel-debugbar`を実行、`composer.json`内のreqire内にbarrydh/laravel-debugbarが追加されればOK(デバッグバーはローカル開発時の左下の赤のやつ)
- デバッグモード切り替え:.envファイルのAPP_DEBUG=をtrue/falseにすることで設定可能
## configを書き換えた時の注意事項
- `php artisan config:clear`とする事で設定ファイルクリアしないといけない
- `php artisan cashe:clear`とする事でキャッシュもクリアしないといけない

## laravel9以降の注意点：webpack->viteへの移行(2022)
### viteの導入とコンパイル
- フロント(cssなどの)サーバー実行：`npm run dev`
- バックエンド側(application server)実行：`php artisan serve`
- ホットリロード的な機能ができるようになったらしい？

## Laravel Breezeについて
[laravel8.x_スターターキット](https://readouble.com/laravel/8.x/ja/starter-kits.html)
- 8.x~からの登場。JsはAlpineJsなどが追加されているが、Vue.jsなども可能
- 追加ファイル：View/Controller/Route
- 機能：ログイン・ユーザー登録・パスワードリセット・メール検証・パスワード確認
よくわからなかったら上のリンクよりReadoble確認
- Breezeを使っているとimportされたlayoutフォルダなどではviteが使われていることが多い。すると8.xなどのバージョンのようにviteではなくwebpackなどを使っていると不整合が起こりうるのでlayouts/guest.blade.phpなどに以下を付け足す
```
<!-- Styles -->
<link rel="stylesheet" href="{{ asset('css/app.css') }}">

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}" defer></script>
```
などと追記する

## laravelのブラウザ表示までの流れ
1. index.phpを実行する(public/index.php)。ここでオートロードや認証機能サービスコンテナな度も読み込まれる。
2. ミドルウエアで認証などをチェックする。(ルーティング移動する前にはさむイメージ)
3. ルーティングを指定し渡す。ここでURL(コントローラとメソッド)を指定し、呼び出す
4. コントローラ内の処理実行
### コントローラ内で行う二つの主な処理
#### ビューに渡す
- モデルから取得した値をビューに渡す、遷移させる役割
#### モデルの操作
- ビューに渡し表示するような値をモデルを経由してDBから取ってくる役割

## 認証機能ファイル
サービスプロバイダconfig/app.php内のproviders, aliasにAuthと記載
- `providers`内では
`Illuminate\Auth\AuthServiceProvider::class,`
`Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,`
- `alias`内では
`'Auth' => Illuminate\Support\Facades\Auth::class,`

などの記述によって印象しているユーザの情報を取得できる

## ルーティング
-> routes/web.php, routes/auth.php に記載。

## エラー表示を日本語化する方法
-> resources/langに「lang-README.md」を分けて記載した。

## tailwindcssとパージ
-> まずpackage.jsonに依存を指定。(devDependenciesの中にある"tailwindcss","forms"の箇所など)
#### tailwindcssとパージのファイル
- tailwindcssはwebpack.mix.js内にあり、Laravel Breezeをインストールした際にimportされるような記載が元からある。
```
//swiperを追記
mix.js('resources/js/app.js', 'public/js').js('resources/js/swiper.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        require("postcss-import"),
        require("tailwindcss"),
        require("autoprefixer"),
    ]);
```
- パージはtailwind.config.js内。Laravel Breezeをインストールした際にimportされている。
元々はpurgeだった。`php artisan serve`(バックエンド側)だけだと反映されないので、`npm run watch`(フロント側)も実行しないといけない。
```
content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
```
## Bladeコンポーネントやコンポーネントについて
->コントローラーを肥大化させないために、viewの詳しい描画などの部分をBladeやコンポーネントに任せている。Contorllerはviewを指定するだけで済む
- `x-コンポーネント名`と書くことで認識される

## コントローラー作成コマンド
`php artisan male:controller コントローラ名`でapp/Http/Controllers以下に作成される