<div>
  {{--// $filename=> コンポーネントの属性にあたる。<x-shop-thumbnail :filename /> の[:filename]の部分--}}
  @if (empty($filename))
  <img src="{{ asset("images/noimage.jpeg") }}">
  @else
  <img src="{{ \Storage::url($filename) }}">
  {{--ここでは\Storageファサードのurl()メソッドを使って画像ファイルのパスを得ていて、
  これによってpublicディレクトリー下のstorage/app/publicディレクトリ下にある
  画像ファイル(url($filename)の部分)にアクセスしている。--}}
  @endif
</div>