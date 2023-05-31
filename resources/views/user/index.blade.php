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
                      <a href=""> 
                        <div class="border rounded-md p-4 max-h-30">
                          {{--productの中にはfilenameが無いので一旦imageFirstに繋いであげる--}}
                          {{--//image1を設定してない場合はnullを入れることにしたので、ここでnull判定をする。文字列の場合は:filename->filenameと書き換える--}}
                            <x-thumbnail filename="{{ $product->imageFirst->filename ?? '' }}" type="products" />
                            <div class="text-gray-700">{{ $product->name}}</div>
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
