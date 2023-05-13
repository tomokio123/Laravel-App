<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ComponentTestController extends Controller
{
    //
    public function showComponent1(){
        $message = "メッセージ１";
        return view("tests.component-test1", compact("message"));
        //tests/component-test1.blade.phpの「:message="$message"」の部分に渡す
        //compact("message")の"messageは"「$message」と同じことを意味する。()内には
        //「渡したい変数から$を取り除き、""で囲ったもの」を入れることで、うまく値が渡ってくれるようになる。
    }
    //
    public function showComponent2(){
        return view("tests.component-test2");
        //そもそもこの「view」は resource/views の中を示している
        //今回はその中にtestsフォルダを作るのである。

        //tests.component-test2は[tests/component-test2.blade.php]と同義である
    }
}

