## viewの仕組みについて

### Componentのパターン
- 一つのコンポーネントを複数ページで使いまわせる、修正しやすい
- スロットを使って文字の部分だけをページによって出し分けたりできる
- resources/views/componentsフォルダ内に配置。
- resources/views/components/testsフォルダの場合は<x-tests.コンポーネント名></x-tests.コンポーネント名>

#### スロット
Conponent側で`{{ $slot }}`とすることで呼び出せる(マスタッシュ構文)
#### 名前付きスロット
- Blade側では`<x-slot name="header">この文章が差し込まれる</x-slot>`と定義し
- Conponent側では`{{ $header }}`として表示することで文章などを自分で名前をつけつつ、差し込めるようになる
(views/dashboard.blade.php など参照)

