<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Owner; //Eloquant エロクアント,Modelのクラスを指定
use Illuminate\Support\Facades\DB; //QueryBuidler クエリビルダー
use Carbon\Carbon;

//「リソースコントローラ」:DBへのCRUD操作を行うために必要なアクション（メソッド）が定義されているコントローラ。
//CRUD操作が必要なページの処理を記述するための叩き台。

// routes/admin.phpにより admin/owners をOwnersControllerに定義すると記述した
class OwnersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct()//まずガードかけて認証する
     {
        $this->middleware("auth:admin");
        //$this->middleware("log")->only("index");
        //$this->middleware("subcribed")->except("store");
     }

     // このコントローラに書いてあることはadmin/owners以下で行うが、
     // そのURLに何もつけない(or /index をつける)と原則的にindexメソッドが呼ばれる!
    public function index()
    {
        $date_now = Carbon::now();
        $date_parse = Carbon::parse(now());
        echo $date_now;
        echo $date_parse;
        $e_all = Owner::all(); //エロくアント
        $q_get = DB::table("owners")->select("name", "created_at")->get(); //クエリビルダ(get->連想配列的に取得)
        //$q_first = DB::table("owners")->select("name")->first(); 
        //クエリビルダ(first->単純なオブジェクトとして取得(ここでは"name"))
        //$c_test = collect([
        //    "name" => "テスト。"
        //]);

        //var_dump($q_first);
        //dd($e_all, $q_get, $q_first, $c_test);

        //変数をビュー側に渡すならcompactメソッド。
        //compactの引数は""で囲んであげる。
        return view("admin.owners.index", compact("e_all", "q_get"));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
