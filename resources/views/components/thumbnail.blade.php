@php
  if($type === "shops"){
    $path = 'storage/shops/';
  }
  if($type === "products"){
    $path = 'storage/products/';
}
@endphp
<div>
  {{--// $filename=> コンポーネントの属性にあたる。<x-shop-thumbnail :filename /> の[:filename]の部分--}}
  @if (empty($filename))
  <img src="{{ asset("images/noimage.jpeg") }}">
  @else
  {{--なんでか知らんけどDBにfilnameをstoreするときに(ShopController参照)、パスがfilenameの先頭に含まれてしまう
  幸か不幸か、それにより画像の認識ができるようになり、ここでurl($filename)の[$filename]で、
  勝手に分類ができているので以下の記述(\Storage::url($filename))のままでOKな気がする--}}
  <img src="{{ \Storage::url($filename) }}">
  {{--ここでは\Storageファサードのurl()メソッドを使って画像ファイルのパスを得ていて、
  これによってpublicディレクトリー下のstorage/app/publicディレクトリ下にある
  画像ファイル(url($filename)の部分)にアクセスしている。--}}
  @endif
</div>