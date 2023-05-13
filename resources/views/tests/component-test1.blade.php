<x-tests.app>
  <x-slot name="header">header1</x-slot>
component-test-1
  <x-tests.card title="タイトル1" content="本文1" />
  {{-- コメント:kresources/views/components/tests/card.blade.phpファイルのcomponent側で設定された
    $title,$contentにそれぞれ値を設定している。 --}}

</x-tests.app>
{{--  <x-tests.app>の始まりのタグは要らないみたいだ？！
testsフォルダのapp.blade.php を示す。xは「resources/views/components」。
name="header"はcomponent側の {{ $header }} の事を示している。
resources/views/components/tests/app.blade.php のslotにこのタグで囲んだ要素を「差し込む」ってこと --}}