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
                            <img class="mx-auto w-30 h-30 object-cover rounded-full" src="{{ asset($replacedFilename) }}">
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
                          <form method="post" action="">
                            <div class="flex justify-around items-center">
                              @csrf
                            <div class="flex justify-around items-center">
                              <span class="mr-2">数量</span>
                              <div class="relative pr-5">
                                <select name="quantity" class="rounded border appearance-none border-gray-300 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 text-base pl-3 pr-10">
                                  {{--@for ($i = 1; $i <= $quantity; $i++)
                                  <option value="{{$i}}">{{$i}}</option>  
                                  @endfor--}}
                                  <option value=""></option>
                                </select>
                              </div>
                            </div>
                            <button class="flex ml-auto text-white-500 bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">カートに入れる</button>
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            </div>
                          </form>
                    
                      </div>
                    </div>
                  </div>
              </div>
          </div>
      </div>
  </div>

  {{--swiper読み込み--}}
  <script src="{{ mix('js/swiper.js')}}"></script>
</x-app-layout>
