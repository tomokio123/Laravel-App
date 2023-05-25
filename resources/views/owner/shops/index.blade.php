<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __('Dashboard') }}(shops/indexのページ。オーナーでログイン時のみ。)
      </h2>
      {{--</h2><!-- layouts/app.blade.php の{{ header }}ところ -->--}}
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 bg-white-500 border-b border-gray-200">
                {{--sessionとして渡ってきた[status]の情報をここに流す--}}
                <x-flash-message status="session('status')" />
                {{--ShopControllerのindex()メソッド内で定義した"$shops"(shopsは店舗IDの集まる配列)をここで使える(routeでこのページに接続できているから)--}}
                  @foreach ($shops as $shop)
                  <div class="w-1/2 p-4">
                  {{-- editの場合はパラメーターも必要。「どのIDに変更をするのか」を指定するために$shopのidを渡せ --}}
                  {{-- 詳しくはShopコントローラーのeditメソッドに定義する。そのコントローラーの定義はroute/owner.phpに定義している --}}
                  <a href="{{ route("owner.shops.edit", ["shop" => $shop->id]) }}"> 
                    {{-- ↑ shops/edit.blade.phpに "shop"オブジェクト (=>渡ったページでは$shopとして使える)を渡している。↑ --}}
                    {{--ShopController の editメソッドの「compact("shops")」の部分に上記の["shop" => $shop->id]が渡る。--}}
                    <div class="border rounded-md p-4">
                        <div class="mb-4">
                            @if ($shop->is_selling)
                            <span class="border p-2 rounded-md bg-blue-400 text-white-500">販売中</span>
                            @else
                            <span class="border p-2 rounded-md bg-red-400 text-white-500">販売停止中</span>
                            @endif
                        </div>
                        <div class="text-xl">
                            {{ $shop->name }}
                        </div>
                        <x-thumbnail :filename="$shop->filename" type="shops"  />
                    </div>
                  </a>
                  </div>
                  @endforeach
              </div>
          </div>
      </div>
  </div>
</x-app-layout>