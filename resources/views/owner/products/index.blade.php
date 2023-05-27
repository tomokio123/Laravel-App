<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __('Dashboard') }}(products/indexのページ)
      </h2>
      {{--</h2><!-- layouts/app.blade.php の{{ header }}ところ -->--}}
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 bg-white-500 border-b border-gray-200">
                {{--sessionとして渡ってきた[status]の情報をここに流す--}}
                <x-flash-message status="session('status')" />
                
                <div class="flex justify-end mb-4">
                  <button onclick="location.href='{{ route("owner.products.create")}}'" class="text-white-500 bg-blue-700 border-0 py-2 px-12 focus:outline-none hover:bg-indigo-600 rounded text-lg">新規登録する</button>
                </div>
                <div class="flex flex-wrap">
                  @foreach ($ownerInfo as $owner)
                  {{--ownerに紐づくshopを取得し更にshopに紐づくproductを取得。--}}
                    @foreach ($owner->shop->product as $product)
                    <div class="w-1/4 p-2 md:p-4">
                      {{--productにはIDが振られているので[主キー]、そのまま$product->idでOK--}}
                      <a href="{{ route("owner.products.edit", ["product" => $product->id]) }}"> 
                        <div class="border rounded-md p-4 max-h-30">
                          {{--productの中にはfilenameが無いので一旦imageFirstに繋いであげる--}}
                            <x-thumbnail :filename="$product->imageFirst->filename" type="products" />
                            {{--<div class="text-gray-700">{{ $product->name}}</div>--}}
                        </div>
                      </a>
                    </div>
                    @endforeach
                  @endforeach
                </div>
                  {{--これを書くだけ({{$owners->links()}})で簡単なページネーションが完成する--}}
                  {{--{{ $images->links() }}--}}
              </div>
          </div>
      </div>
  </div>
</x-app-layout>