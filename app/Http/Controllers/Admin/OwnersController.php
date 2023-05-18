<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        dd("オーナー一覧です");
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
