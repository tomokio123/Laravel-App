{{-- <x-tests.app> --}}
<x-tests.app>
  <x-slot name="header">header2</x-slot>
    component2
    <x-tests.card title="タイトル2" content="本文2"></x-tests.card>
    <x-test-class-base />{{--testディレクトリの中だから「test.」が要るのかと思ったが、
      test-class-baseファイルは今のところ一意なので多分書いてはいけないのだろう --}}

    <x-test-class-base classBaseMessage="test2.bladeでmessageを上書き" />
    <div class="mb-4"></div>
    <x-test-class-base classBaseMessage="est2.bladeでclassBaseMessageを上書き" defaultMessage="test2.bladeでdefaultMessageを上書き" />

    {{-- <!-- そもそも<x-test-class-base classBaseMessage="メッ世辞です" />は、
      「test-class-base-component.bladeファイルの$$classBaseMessageに"メッ世辞です"という値を入れ、
      その状態の同ファイルを表示する」ってこと。また、このままでは上の「classBaseMessage」は
      使用する箇所(<div>{{ $classBaseMessage }}</div>)では、まだ定義されていないことになるのでTestClassBaseクラスで
    初期化メソッドを回す必要がある。それが「public function __construct($classBaseMessage){}」である-->
    <!-- TestClassBaseクラスにも変数定義(classBaseMessageを定義)しないといけない  --> --}}
</x-tests.app>
