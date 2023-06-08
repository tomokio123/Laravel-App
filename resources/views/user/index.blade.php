@php
use App\Consts\PrefectureConst; //importしないと使えないっぽい？
use Illuminate\Http\Request;
@endphp
<x-app-layout>
  <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('商品一覧') }}
        </h2>
    
          {{--ページネーションが絡んでくるのでgetを指定しておく--}}
          <form method="get" action="{{ route("user.items.index") }}">
            <div class="lg:flex lg:justify-around">
              <div class="lg:flex items-center">
                <select name="category" class="mb-2 lg:mb-0 lg:mr-2 rounded">
                  {{--すべての選択肢--}}
                  リロードした時などに値を保持したいのでRequestの値を
                  <option value="0" @if(\Request::get("category") === "0") selected @endif>
                    全て
                  </option>
                  {{--ここまではPrimaryCategoryであり、非活性の(ラベルのみの)<optgroup>タグに表示する。--}}
                  @foreach($categories as $category)
                    <optgroup label="{{ $category->name }}">
                     {{--それぞれのPrimaryCategoryに紐づいているSecondaryCategoryを取り出して、<option>で選べるようにする--}}
                     @foreach($category->secondary as $secondary)
                       <option value="{{ $secondary->id }}" @if(\Request::get("category") == $secondary->id) selected @endif >
                        {{ $secondary->name }}
                       </option>
                     @endforeach
                  @endforeach
                </select>
                <div class="flex space-x-2 items-center">
                  <div><input name="keyword" class="border rounded border-gray-500 p-2" placeholder="キーワードを入力"></div>
                  <div><button class="ml-auto text-white-500 bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">検索する</button></div>
                </div>
              </div>
              <div class="flex">
                <div>
                  <span class="text-sm">表示順</span><br>
                  <select id="sort" name="sort" class="mr-4 bg-white rounded">
                    <option value="{{ PrefectureConst::SORT_ORDER['recommend']}}"
                    {{--ページをリロードなどしても、並び順がデフォルトに戻らないようにgetリクエストの"sort"に値があればそれを適用--}}
                      @if(\Request::get('sort') === PrefectureConst::SORT_ORDER['recommend'] ) 
                      selected 
                      @endif>おすすめ順
                    </option>
                    <option value="{{ PrefectureConst::SORT_ORDER['higherPrice']}}"
                      @if(\Request::get('sort') === PrefectureConst::SORT_ORDER['higherPrice'] ) 
                      selected 
                      @endif>料金の高い順
                    </option>
                    <option value="{{ PrefectureConst::SORT_ORDER['lowerPrice']}}"
                      @if(\Request::get('sort') === PrefectureConst::SORT_ORDER['lowerPrice'] ) 
                      selected 
                      @endif>料金の安い順 
                    </option>
                    <option value="{{ PrefectureConst::SORT_ORDER['later']}}"
                      @if(\Request::get('sort') === PrefectureConst::SORT_ORDER['later'] ) 
                      selected 
                      @endif>新しい順
                    </option>
                    <option value="{{ PrefectureConst::SORT_ORDER['older']}}"
                      @if(\Request::get('sort') === PrefectureConst::SORT_ORDER['older'] ) 
                      selected 
                      @endif>古い順
                    </option>
                  </select>
                </div>

                <div>
                <span class="text-sm">表示件数</span><br>
                {{--下のjsで呼び出す時のgetElement指定しているIDがここのid=のところ--}}
                <select id="pagination" name="pagination" class="rounded">
                  {{--(selectタグのnameが)キー、(optionタグのvalueが)バリュー、の関係になっている--}}
                  <option value="20"
                      @if(\Request::get('pagination') === '20')
                      selected
                      @endif>20件
                  </option>
                  <option value="50"
                      @if(\Request::get('pagination') === '50')
                      selected
                      @endif>50件
                  </option>
                  <option value="100"
                      @if(\Request::get('pagination') === '100')
                      selected
                      @endif>100件
                  </option>
                </select>
              </div>
            </div>
          </div>
          </form>
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
                {{--ページネーション表示--}}
                {{--getパラメータのソート順がページネーション時に戻ってしまうのでそこを修正するためにappendsを噛ませる--}}
                {{ $products->appends([
                  'sort' => \Request::get("sort"),
                 "pagination" => \Request::get("pagination")
                ])->links() }}
              </div>
          </div>
      </div>
  </div>
  <script>
    const select = document.getElementById('sort') //selectタグ内にあるid='sort'の箇所を指す
    select.addEventListener("change", function(){
      //↓:指定したidの箇所が変化したら("change")function回す。→このidを囲むformのsubmitする(ここでは表示順のoption欄を変更したら勝手にsubmitが回ってoptionタグのvalueが変更され、上書きされる仕組み)
      this.form.submit() 
      //thisはここではselectオブジェクトのことなはず(this = 「自分自身」なので、「自分自身の他のオブジェクトメソッドを使用する」の意味だと思う)
    }) //jsで間違えていたら動かなかったので注意

    const paginate = document.getElementById('pagination') //selectタグ内にあるid='pagination'の箇所を指す
    paginate.addEventListener("change", function(){
      this.form.submit() 
    }) 
  </script>
</x-app-layout>
