<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __('Dashboard') }}
      </h2>
      {{--</h2><!-- layouts/app.blade.php の{{ header }}ところ -->--}}
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 bg-white border-b border-gray-200">
                <x-auth-validation-errors class="mb-4" :errors="$errors" />
                {{--画像アップロードするためにはenctypeがいる--}}
                <form method="post" action="{{ route("owner.shops.update", ["shop" => $shop->id]) }}" enctype="multipart/form-data">
                  @csrf
                  <div class="-m-2">
                    <div class="p-2 w-1/2 mx-auto">
                      <div class="relative">
                        <label for="image" class="leading-7 text-sm text-gray-600">画像</label>
                        {{--controllerで使う[image]とはinputタグのnameのこと。また画像アップロードする際はvalue属性は使えない --}}
                        {{--accept属性で受け入れる画像のファイル形式を指定できる--}}
                        <input type="file" id="image" name="image" accept="image/png, image/jpeg, image/jpg" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                      </div>
                    </div>
                    <div class="flex justify-around p-2 w-full mt-12 mb-12">
                        <button type="button" onclick="location.href='{{ route("owner.shops.index")}}'" class="text-white-500 bg-blue-600 border-0 py-2 px-12 focus:outline-none hover:bg-indigo-600 rounded text-lg">戻る</button>
                        <button type="submit" class="text-white-500 bg-blue-600 border-0 py-2 px-12 focus:outline-none hover:bg-indigo-600 rounded text-lg">更新する</button>
                    </div>
                  </div>
                </form>
              </div>
          </div>
      </div>
  </div>
</x-app-layout>