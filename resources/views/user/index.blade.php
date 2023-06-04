<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __('ホーム') }}
      </h2>
      {{--<!-- layouts/app.blade.php の{{ header }}ところ -->--}}
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex flex-wrap">
                  {{--@foreach ($ownerInfo as $owner)--}}
                  {{--ownerに紐づくshopを取得し更にshopに紐づくproductを取得。--}}
                    @foreach ($products as $product)
                    <div class="w-1/4 p-2 md:p-4">
                      {{--productにはIDが振られているので[主キー]、そのまま$product->idでOK--}}
                      {{--ルート先(商品詳細)にIDを渡せるようにitem(ルートパラメータ)・['item' => $product->id]を渡す--}}
                      <a href="{{ route("user.items.show", ['item' => $product->id]) }}">
                        <div class="border rounded-md p-4 max-h-30 bg-white-500">
                          {{--productの中にはfilenameが無いので一旦imageFirstに繋いであげる--}}
                          {{--//image1を設定してない場合はnullを入れることにしたので、ここでnull判定をする。文字列の場合は:filename->filenameと書き換える--}}
                            <x-thumbnail filename="{{ $product->filename ?? '' }}" type="products" />
                              <div class="mt-4">
                                {{--id確認したい時は以下のようにデバッグするのもあり--}}
                                {{--<h3 class="text-gray-600 text-xs tracking-widest title-font mb-1">{{ $product->id }}</h3>--}}
                                <h3 class="text-gray-600 text-xs tracking-widest title-font mb-1">{{ $product->category }}</h3>
                                <h2 class="text-gray-900 title-font text-lg font-medium">{{ $product->name }}</h2>
                                <p class="mt-1">{{ number_format($product->price) }} <span class="text-sm text-gray-700">円(税込)</span></p>
                              </div>
                            {{--<div class="text-gray-700">{{ $product->name}}</div>--}}
                        </div>
                      </a>
                    </div>
                    @endforeach
                  {{--@endforeach--}}
                </div>
              </div>
          </div>
      </div>
  </div>
</x-app-layout>
