<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ComponentTestController extends Controller
{
    //
    public function showComponent1(){
        return view("tests.component-test1");
    }
    //
    public function showComponent2(){
        return view("tests.component-test2");
        //そもそもこの「view」は resource/views の中を示している
        //今回はその中にtestsフォルダを作るのである。

        //tests.component-test2は[tests/component-test2.blade.php]と同義である
    }
}

