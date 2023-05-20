<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        オーナーいち覧
          {{--{{ __('Dashboard') }}--}}
      </h2>
      {{--<!-- layouts/app.blade.php の{{ header }}ところ -->--}}
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 bg-white border-b border-gray-200">
                <section class="text-gray-600 body-font">
                  <div class="container px-5 mx-auto">
                    {{--sessionとして渡ってきた[status]の情報をここに流す--}}
                    <x-flash-message status="session('status')" />
                    <div class="flex justify-end mb-4">
                      <button onclick="location.href='{{ route("admin.owners.create")}}'" class="text-white-500 bg-blue-700 border-0 py-2 px-12 focus:outline-none hover:bg-indigo-600 rounded text-lg">新規登録する</button>
                    </div>
                    <div class="lg:w-2/3 w-full mx-auto overflow-auto">
                      <table class="table-auto w-full text-left whitespace-no-wrap">
                        <thead>
                          <tr>
                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">名前</th>
                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">メール</th>
                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">作成日</th>
                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">作成日</th>
                            {{--<th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Price</th>--}}
                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tr rounded-br"></th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ( $owners as $owner)
                          <tr>
                            <td class="px-4 py-3">{{ $owner->name }}</td>
                            <td class="px-4 py-3">{{ $owner->email }}</td>
                            <td class="px-4 py-3">{{ $owner->created_at->diffForHumans() }}</td>
                            <td class="px-4 py-3">
                              {{--$ownerのidはOwnersController.phpで取得・設定する--}}
                              <button onclick="location.href='{{ route('admin.owners.edit', ['owner'=> $owner->id]) }}'" class="text-white-500 bg-indigo-500 border-0 py-2 px-4 focus:outline-none hover:bg-indigo-600 rounded">編集</button>
                            </td>
                            {{--actionの中の意味は「route()内で[admin/owners](OwnersController)内の[destroy]メソッドを呼び出す」ってこと↓--}}
                            {{--第二引数でownerのidを渡す必要があるので編集と同じIDを渡せばOK--}}
                            <form id="delete_{{ $owner->id }}" method="post" action="{{ route("admin.owners.destroy", ['owner'=> $owner->id]) }}">
                              {{--フォームを送る際には必ずシーサーフ(@csrf)が必要--}}
                              @csrf 
                              @method("delete")
                              <td class="px-4 py-3">
                                {{-- data-idは自由に作った属性 --}}
                                {{--onclickには下のJsのコードで作成したdeletePostメソッドを当てはめる--}}
                                {{--data-〇〇としてカスタム属性とすることができる。、〇〇には好きにHTMLのプロパティを定義する--}}
                                <a href="#" data-id="{{ $owner->id }}" onclick="deletePost(this)" class="text-white-500 bg-red-400 border-0 py-2 px-4 focus:outline-none hover:bg-red-500 rounded">削除</a>
                              </td>
                            </form>
                          </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>
                </section>

                  {{--エロくアント 
                  @foreach ($e_all as $e_owner)
                    {{ $e_owner->name }}
                    {{ $e_owner->created_at->diffForHumans() }}
                  @endforeach
                  <br>
                  クエリビルダ
                  @foreach ($q_get as $q_owner)
                    {{ $q_owner->name }}
                    {{--{{ $q_owner->created_at->diffForHumans() }} 
                    →クエリビルダではまたCarbonインスタンスではないのでfiffForHumansが使えない--}}
                    {{--{{ Carbon\Carbon::parse($q_owner->created_at)->diffForHumans()}}
                  @endforeach--}}
              </div>
          </div>
      </div>
  </div>
  <script>
    function deletePost(e) {
      "use strict";
      if(confirm("本当に削除してもいいでスカ？")) {
        document.getElementById("delete_" + e.dataset.id).submit();
      }
    }
  </script>
</x-app-layout>
