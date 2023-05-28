<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __('Dashboard') }}
      </h2>
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 bg-white border-b border-gray-200">
                <x-auth-validation-errors class="mb-4" :errors="$errors" />  
                <form method="post" action="{{ route('owner.products.store')}}" >
                    @csrf
                    <div class="-m-2">
                      <div class="p-2 w-1/2 mx-auto">
                        <div class="relative">
                          <label for="category" class="leading-7 text-sm text-gray-600">カテゴリー</label>
                          <select name="category" id="category" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            {{--ここまではPrimaryCategoryであり、非活性の(ラベルのみの)<optgroup>タグに表示する。--}}
                            @foreach($categories as $category)
                             <optgroup label="{{ $category->name }}">
                              {{--それぞれのPrimaryCategoryに紐づいているSecondaryCategoryを取り出して、<option>で選べるようにする--}}
                              @foreach($category->secondary as $secondary)
                                <option value="{{ $secondary->id}}" >
                                 {{ $secondary->name }}
                                </option>
                              @endforeach
                            @endforeach
                           </select>
                        </div>
                      </div>
                      <x-select-image :images="$images" name="image1" />
                      {{--<x-select-image :images="$images" name="image1" />
                      <x-select-image :images="$images" name="image2" />
                      <x-select-image :images="$images" name="image3" />
                      <x-select-image :images="$images" name="image4" />
                      <x-select-image :images="$images" name="image5" />
                      <div class="p-2 w-1/2 mx-auto">
                        <div class="relative flex justify-around">
                          <div><input type="radio" name="is_selling" value="1" class="mr-2" checked>販売中</div>
                          <div><input type="radio" name="is_selling" value="0" class="mr-2" >停止中</div>
                        </div>
                      </div>--}}
                      <div class="p-2 w-1/2 mx-auto">
                        <div class="relative flex justify-around">
                          <div><input type="radio" name="is_selling" value="1" class="mr-2" checked>販売中</div>
                          <div><input type="radio" name="is_selling" value="0" class="mr-2" >停止中</div>
                        </div>
                      </div>
                      <div class="p-2 w-full flex justify-around mt-4">
                        <button type="button" onclick="location.href='{{ route('owner.products.index')}}'" class="bg-gray-200 border-0 py-2 px-8 focus:outline-none hover:bg-gray-400 rounded text-lg">戻る</button>
                        <button type="submit" class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">登録する</button>                        
                      </div>
                    </div>
                  </form>
              </div>
          </div>
      </div>
  </div>
  <script>
    'use strict' //'use strict'=厳し目にチェック
    const images = document.querySelectorAll('.image')
    //document.querySelectorAll('.クラス名') で「クラス名」がついているクラス全てを取り出している
    
    images.forEach( image =>  {
      image.addEventListener('click', function(e){ //addEventListener = clickした時の動作を設定できる。(第一引数はイベントの引き金になる動作の種類・第二引数は追加するイベント)
        const imageName = e.target.dataset.id.substr(0, 6)//image1~4 などを取得
        const imageId = e.target.dataset.id.replace(imageName + '_', '') //image1_10と来たら「image1_」の部分だけ''に置き換える(要は後ろのidだけ抜き取る)
        const imageFile = e.target.dataset.file.replace('public/products', 'storage/products')
        const imagePath = e.target.dataset.path
        const modal = e.target.dataset.modal
        document.getElementById(imageName + '_thumbnail').src = '/' +imageFile
        document.getElementById(imageName + '_hidden').value = imageId
        MicroModal.close(modal);
    }, )
    })  
  </script>
</x-app-layout>

{{--@foreach ($categories as $category)
                          <optgroup label="{{ $category->name }}">
                            @foreach ($category->secondary as $secondary)
                              <option value="{{ $secondary->id }}">
                                {{ $secondary->name }}
                              </option>
                            @endforeach
                          @endforeach--}}