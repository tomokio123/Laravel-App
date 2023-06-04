<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TestClassBase extends Component
{

    public $classBaseMessage;//プロパティ(フィールド変数)を登録する。ということは初期化が必須であるので、、
    //以下の__constructメソッド(初期化処理)に移る。
    public $defaultMessage;

    /**
     * Create a new component instance.
     *
     * @return void
     */

    //public $classBaseMessage;のフィールで変数を初期化完了したものをclassBaseMessageとし、bladeファイルの
    //classBaseMessage="メッ世辞です"とリンクさせる。
    public function __construct($classBaseMessage = "classBaseMessage初期値", $defaultMessage = "defaultMessage初期値")
    {
        //classなので初期化できる。コンストラクタ。
        $this->classBaseMessage = $classBaseMessage;
        $this->defaultMessage = $defaultMessage;
        //classBaseMessageはtest-class-base.blade側
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    //呼び出されたらまずはcontrollerのrenderメソッドが動く
    public function render()//renderメソッドで記載されているビューに渡す
    {
        //コンストラクタで初期値設定している場合はrenderメソッドで値を渡さずとも問題なく値は渡る
        return view('components.tests.test-class-base-component');
    }
}
