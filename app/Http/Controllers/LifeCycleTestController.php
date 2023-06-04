<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LifeCycleTestController extends Controller
{
    public function showServiceProviderTest(){
        $encrypt = app()->make("encrypter");
        $password = $encrypt->encrypt("password");

        $sample = app()->make("serviceProviderTest");
        //"serviceProviderTest"というキーワードで登録されているはずなのでそれを取ってきて$sampleに格納
        //ちなみに登録はSampleServiceProvider.phpの
        //register()メソッド内で「->bind("serviceProviderTest", function(){}」として登録している。
        dd($sample, $password, $encrypt->decrypt($password));
    }

    //showServiceContainerTestメソッド
    public function showServiceContainerTest(){
        //Containerに「登録」するには「->bind」を利用する。
        app()->bind("lifeCycleTest", function(){
            return "ライフサイクルテスト";
        });
        //ddで出力した時、bindingsの"lifeCycleTest"の中の"concrete"からどこで登録したとか色々見れる

        $test = app()->make("lifeCycleTest");

        // //サービスコンテナ無しのパターン->インスタンス化が必須
        // $message = new Message();//インスタンス化
        // $sample = new Sample($message);//インスタンス化
        // $sample->run();

        //サービスコンテナapp()パターン
        app()->bind("sample", Sample::class);//bind("キーとなる文字列", 読み込みたいクラス名::class);
        //Containerに登録したContainerをmakeすれば
        $sample = app()->make("sample");
        //runできるようになるし、
        //$messageをインスタンス化せずに$sampleオブジェクトのrunメソッドを呼び出せることがデカい
        $sample->run();

        dd($test, app());
    }
}

class Sample{
    public $message;
    public function __construct(Message $message){
       $this->message = $message; 
    }
    public function run(){
        $this->message->send();
    }
}
class Message{
    public function send(){
        echo "メッセージ表示!";
    }
}