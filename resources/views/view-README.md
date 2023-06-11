## viewの仕組みについて
ビューのキャッシュクリアコマンド:`php artisan view:clear`
### Componentのパターン
- 一つのコンポーネントを複数ページで使いまわせる、修正しやすい
- スロットを使って文字の部分だけをページによって出し分けたりできる
- resources/views/componentsフォルダ内に配置。
- resources/views/components/testsフォルダの場合は<x-tests.コンポーネント名></x-tests.コンポーネント名>
- コンポーネントの中にそのままクラスを設定してもCSSなどが有効にならない。のでコンポーネント側クラスとコンポーネントを使う側のクラスを混ぜて使うときは、「使う側」のクラス属性の前に`{{ attributes->merge([連想配列]) }}`と記述してあげる事で「使う側」・「使われるコンポーネント側」両方のCSSを指定したクラスが両方とも反映されるようにできる。これを属性BAGという。

#### スロット
Conponent側で`{{ $slot }}`とすることで呼び出せる(マスタッシュ構文)
#### 名前付きスロット
- Blade側では`<x-slot name="header">この文章が差し込まれる</x-slot>`と定義し
- Conponent側では`{{ $header }}`として表示することで文章などを自分で名前をつけつつ、差し込めるようになる
(views/dashboard.blade.php など参照)
#### 名前付きスロットで「属性」の受け渡し
`<x-slot title="タイトル" content="本文">`などと「属性」を定義し値が入っていると,bladeファイル内で`{{ $title }}`,`{{ $content }}`とすることで渡すことが可能になる
#### 名前付きスロットで「変数」の受け渡し
`<x-slot :title="$message">`などと「コロン」をつけて「変数」を定義し値が入っていると, bladeファイル内で`{{ $title }}`とすることで渡すことが可能になる。「$message」のように渡す変数には「$」をつける。また、$messageなどはコントローラ側で`compact("message")`などとしてcomponentのスロットに渡すことができる

※変数を使う際は初期値の設定もできる。初期値がないとアプリが落ちるなどの障害も発生しやすくなる。
例
```
@props([
  // "title", ←的な感じで、初期値が確約されているなら空っぽでもいい。
  "title" => "title初期値です",
  "message" => "message初期値です",
  "content" => "content初期値です"
])
```
### クラスベース
### クラスベースを作るコマンド
`php artisan make:component XXXX`とする事で、app>View>Componets>XXXX.php(コンポーネントを呼ぶクラス)([TestClassBase]のような感じのクラス)が生成される。それと同時に、app>resources>componets>YYYY.blade.php(実際に呼ばれるbladeコンポーネント)も作成される。
Blade内では`<x-test-class-base>`として使う(呼び出す)。以下にまとめると
1. 使いたいbladeファイル内でコンポーネントの「クラス」を指定し(`<x-test-class-base>`)、
2. そのクラス内のrender()メソッド内に書いてあるview()ヘルパ関数が呼び出され、
3. そのviewに指定してある「クラスベースコンポーネント」が表示される
という順番で利用できる。初期値設定は以下の順番で可能。
- app>View>Componets>XXXX.phpに`public $classBaseMessage`のようにフィールド変数を持たせた上で、
- その下にコンストラクタメソッド`public function __construct()`を設定できる。

