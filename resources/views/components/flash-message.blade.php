@props(["status" => "info"])
{{-- @props(['type' => 'info', 'message'])-- といった具合に、}}
{{-- @propsはデータ変数と見なすべき属性を指定できる。その属性が元々データ変数の場合は、変数の名前を配列キーに指定し(=>)、
  初期値を指定できる --}}

{{--$statusに入ってくる値によって色を出し分ける。--}}
@php
  if($status === "info"){ $bgColor = "bg-blue-500"; }
  if($status === "error"){ $bgColor = "bg-red-300"; }
@endphp

@if (session("message"))
  <div class="{{ $bgColor }} w-1/2 mx-auto p-2 text-white-500">
    {{ session("message")}}
  </div>
@endif