@php
if ($name === 'image1') { $modal = 'modal-1';}
if ($name === 'image2') { $modal = 'modal-2';}
if ($name === 'image3') { $modal = 'modal-3';}
if ($name === 'image4') { $modal = 'modal-4';}
@endphp

{{--<div class="wrapper">
  <button type="button" data-micromodal-trigger="modal-middle" href='javascript:;'>Open middle modal</button>
</div>--}}

{{--https://gist.github.com/ghosh/4f94cf497d7090359a5c9f81caf60699 のmicromodal.htmlを貼りつけ--}}
{{--①mimi-modal--}}
<div class="modal micromodal-slide" id="{{ $modal }}" aria-hidden="true">
  <div class="modal__overlay" tabindex="-1" data-micromodal-close>
    <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="{{ $modal }}-title">
      <header class="modal__header">
        <h2 class="pr-6 text-indigo-800" id="{{ $modal }}-title">
          ファイルを選択してください
        </h2>
        <button type="button" class="modal__close"  aria-label="Close modal" data-micromodal-close></button>
      </header>
      <main class="modal__content" id="{{ $modal }}-content">
        <div class="flex flex-wrap">
          {{--呼び出し元から[$images]を渡す必要があるのでここではproduct/create.blade.phpで<x-select-image :images="$images" >とすることで渡せている--}}
          @foreach ($images as $image)
          <div class="w-1/4 p-2 md:p-4">
              <div class="border rounded-md p-4 max-h-30">
                {{--data-〇〇=""とすることで""の部分をjsのe.target.dataset.〇〇. あたりから見つけ出せるようにしている--}}
                <img class="image" 
                data-id="{{ $name }}_{{ $image->id }}" 
                {{-- ↑ [image1_10]的な感じになる --}}
                data-file="{{ $image->filename }}"
                {{--以下表示されなかったら注意。  <img src="{{ \Storage::url($filename) }}">か？？--}}
                data-path="{{ asset('storage/products/') }}"
                data-modal="{{ $modal }}"
                src="{{ \Storage::url($image->filename) }} ">
                <div class="text-gray-700">{{ $image->title }}</div>
              </div>
            </a>
          </div>
          @endforeach
        </div>
      </main>
      <footer class="modal__footer">
        <button type="button" class="modal__btn" data-micromodal-close aria-label="閉じる">閉じる</button>
      </footer>
    </div>
  </div>
</div>

<div class="flex justify-around items-center mb-4">
  <a data-micromodal-trigger="{{ $modal }}" href='javascript:;'>ファイルを選択</a>
  <div class="w-1/4">
    {{--$nameにimage1やimage2などが入ってくる--}}
    <img id="{{ $name }}_thumbnail" src="">
    {{--id="image1_thumbnail"などといったidを持たせてjs側から呼び出す--}}
  </div>
</div>
{{-- [name= ]には image1などを入れたい--}}
<input id="{{ $name }}_hidden" type="hidden" name="{{ $name }}" value="">

{{--②midlle-modal--}}
{{--<div class="modal micromodal-slide" id="{{ $modal }}" aria-hidden="true">
  <div class="modal__overlay" tabindex="-1" data-micromodal-close>
    <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="{{ $modal }}-title">
      <header class="modal__header">
        <h2 class="modal__title" id="{{ $modal }}-title">
          お
        </h2>
        <button type="button" class="modal__close" aria-label="Close modal" data-micromodal-close></button>
      </header>
      <main class="modal__content" id="{{ $modal }}-content">
        <p>
          Try hitting the <code>tab</code> key and notice how the focus stays within the modal itself. Also, <code>esc</code> to close modal.
        </p>
      </main>
      <footer class="modal__footer">
        <button type="button" class="modal__btn modal__btn-primary">Continue</button>
      </footer>
    </div>
  </div>
</div>--}}
