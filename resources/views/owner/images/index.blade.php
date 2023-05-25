<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __('Dashboard') }}(images/indexのページ。オーナーでログイン時のみ。)
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
                  <button onclick="location.href='{{ route("owner.images.create")}}'" class="text-white-500 bg-blue-700 border-0 py-2 px-12 focus:outline-none hover:bg-indigo-600 rounded text-lg">新規登録する</button>
                </div>
                  @foreach ($images as $image)
                  <div class="w-1/4 p-4">
                  <a href="{{ route("owner.images.edit", ["image" => $image->id]) }}"> 
                    <div class="border rounded-md p-4">
                        <div class="text-xl">
                            {{ $image->title }}
                        </div>
                        <x-thumbnail :filename="$image->filename" type="products" />
                    </div>
                  </a>
                  </div>
                  @endforeach

                  {{--これを書くだけ({{$owners->links()}})で簡単なページネーションが完成する--}}
                  {{ $images->links() }}
              </div>
          </div>
      </div>
  </div>
</x-app-layout>