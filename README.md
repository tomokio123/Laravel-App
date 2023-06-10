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
