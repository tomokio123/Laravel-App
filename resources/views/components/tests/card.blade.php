@props([//prppsを使う場合は全ての変数において初期値を設定しなはれ
  // "title", ←的な感じで、初期値が確約されているなら空っぽでもいい。
  "title" => "title初期値です",
  "message" => "message初期値です",
  "content" => "content初期値です"
  ])
<div class="border-2 shadow-md w- 1/4 p-2">
  <div>{{ $title }}</div><!-- component側で変数を設定し($title等)、blade側で取り出す-->
  <div>画像</div>
  <div>{{ $content }}</div>
  <div>{{ $message }}</div>
</div>