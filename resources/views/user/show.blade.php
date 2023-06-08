<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __('商品詳細/show') }}
      </h2>
      {{--<!-- layouts/app.blade.php の{{ header }}ところ -->--}}
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 bg-white-500 border-b border-gray-200">
                  <div class="md:flex md:justify-around">
                    <div class="md:w-1/2 ml-4">
                      <!-- Slider main container -->
                      <div class="swiper-container">
                        <!-- Additional required wrapper -->
                        <div class="swiper-wrapper">
                          <!-- Slides -->
                          <div class="swiper-slide">
                            @php
                              $filename = $product->imageFirst->filename;
                              $replacedFilename = str_replace('public/products/', 'storage/products/', $filename); 
                            @endphp
                            @if ($filename !== null)
                            <img class="mx-auto w-30 h-30 object-cover" src="{{ asset($replacedFilename) }}">
                            @else
                            <img src="{{ asset("images/noimage.jpeg") }}">
                            @endif
                          </div>
                          <div class="swiper-slide">
                            @php
                              $filename = $product->imageSecond->filename;
                              $replacedFilename = str_replace('public/products/', 'storage/products/', $filename); 
                            @endphp
                            @if ($filename !== null)
                            <img class="mx-auto w-30 h-30 object-cover" src="{{ asset($replacedFilename) }}">
                            @else
                            <img src="{{ asset("images/noimage.jpeg") }}">
                            @endif
                          </div>
                          <div class="swiper-slide">
                            @php
                              $filename = $product->imageThird->filename;
                              $replacedFilename = str_replace('public/products/', 'storage/products/', $filename); 
                            @endphp
                            @if ($filename !== null)
                            <img class="mx-auto w-30 h-30 object-cover" src="{{ asset($replacedFilename) }}">
                            @else
                            <img src="{{ asset("images/noimage.jpeg") }}">
                            @endif
                          </div>
                          <div class="swiper-slide">
                            @php
                              $filename = $product->imageFourth->filename;
                              $replacedFilename = str_replace('public/products/', 'storage/products/', $filename); 
                            @endphp
                            @if ($filename !== null)
                            <img class="mx-auto w-30 h-30 object-cover" src="{{ asset($replacedFilename) }}">
                            @else
                            <img src="{{ asset("images/noimage.jpeg") }}">
                            @endif
                          </div>
                          
                          ...
                        </div>
                        <!-- If we need pagination -->
                        <div class="swiper-pagination"></div>

                        <!-- If we need navigation buttons -->
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>

                        <!-- If we need scrollbar -->
                        <div class="swiper-scrollbar"></div>
                      </div>
                    </div>
                    <div class="md:w-1/2 p-4">
                      <h2 class="text-sm title-font text-gray-600 tracking-widest mb-1">{{ $product->category->name }}</h2>
                      <h1 class="text-gray-900 text-3xl title-font font-medium mb-4">{{ $product->name }}</h1>
                      <p class="leading-relaxed">{{ $product->information }}</p>
                      <div class="flex justify-around items-center mt-5">
                        <div>
                          <span class="title-font font-medium text-2xl text-gray-900">{{ number_format($product->price) }}</span><span class="text-sm text-gray-700">円(税込)</span>
                        </div>
                          <form method="post" action="{{ route("user.cart.add") }}">
                             {{--action属性にrouteを定義し、method属性にHTTP動詞を定義--}}
                            <div class="flex justify-around items-center">
                              @csrf
                            <div class="flex justify-around items-center">
                              <span class="mr-2">数量</span>
                              <div class="relative pr-5">
                                {{--在庫情報をPOST通信でDb保存sする必要があるののでname=quantityとしておく--}}
                                <select name="quantity" class="rounded border appearance-none border-gray-300 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 text-base pl-3 pr-10">
                                  {{--quantityの数だけfor文を回す。在庫が0の183番の商品はこのoptionの中身が空白になる--}}
                                  {{--考察:quantityがゼロの時に何かアラートを表示するような仕組みもアリだなと思った--}}
                                  @for ($i = 1; $i <= $quantity; $i++)
                                  <option value="{{$i}}">{{$i}}</option>  
                                  @endfor
                                </select>
                              </div>
                            </div>
                            <button class="flex ml-auto text-white-500 bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">カートに入れる</button>
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            {{--typeがhiddenのinputを用意することで、$product->idの情報も渡せる--}}
                            </div>
                          </form>
                    
                      </div>
                    </div>{
                  </div>
                  <div class="border-t border-gray-400 my-8"></div>
                  <div class="mb-4 text-center">この商品を販売しているショップ</div>
                  <div class="mb-4 text-center">{{ $product->shop->name }}</div>
                  <div class="mb-4 text-center">
                    @php
                    $shopFilename = $product->shop->filename;
                    $replacedShopFilename = str_replace('public/shops/', 'storage/shops/', $shopFilename); 
                    @endphp
                    @if ($shopFilename !== null)
                      <img class="mx-auto w-40 h-40 object-cover rounded-full" src="{{ asset($replacedShopFilename)}}">
                    @else
                      <img src="">
                    @endif
                  </div>
                  <div class="mb-4 text-center">
                    {{--post通信に影響を出したくないため、type="button"とする--}}
                    <button type="button" data-micromodal-trigger="modal-1" href='javascript:;' class="text-white-500 bg-gray-400 border-0 py-2 px-6 focus:outline-none hover:bg-gray-500 rounded">ショップの詳細を見る</button>
                  </div>
              </div>
          </div>
      </div>
  </div>

  {{--micromodeal--}}
  <div class="modal micromodal-slide" id="modal-1" aria-hidden="true">
    <div class="modal__overlay z-50" tabindex="-1" data-micromodal-close>
      <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
        <header class="modal__header">
          <h2 class="modal__title" id="modal-1-title">
            {{ $product->shop->name }}
          </h2>
          <button type="button" class="modal__close" aria-label="Close modal" data-micromodal-close></button>
        </header>
        <main class="modal__content" id="modal-1-content">
          <p>
            {{ $product->shop->information }}
          </p>
        </main>
        <footer class="modal__footer">
          <button type="button" class="modal__btn" data-micromodal-close aria-label="Close this dialog window">閉じる</button>
        </footer>
      </div>
    </div>
  </div>

  {{--swiper読み込み--}}
  <script src="{{ mix('js/swiper.js')}}"></script>
</x-app-layout>
