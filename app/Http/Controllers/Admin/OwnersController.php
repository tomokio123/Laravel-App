<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Owner; //Eloquant エロクアント,Modelのクラスを指定
use Illuminate\Support\Facades\DB; //QueryBuidler クエリビルダー
use Carbon\Carbon;
use Illuminate\Validation\Rules;

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
        //$date_now = Carbon::now();
        //$date_parse = Carbon::parse(now());
        //echo $date_now;
        //echo $date_parse;
        //$e_all = Owner::all(); //エロくアント
        //$q_get = DB::table("owners")->select("name", "created_at")->get(); //クエリビルダ(get->連想配列的に取得)
        //$q_first = DB::table("owners")->select("name")->first(); 
        //クエリビルダ(first->単純なオブジェクトとして取得(ここでは"name"))
        //$c_test = collect([
        //    "name" => "テスト。"
        //]);

        //var_dump($q_first);
        //dd($e_all, $q_get, $q_first, $c_test);

        //変数をビュー側に渡すならcompactメソッド。
        //compactの引数は""で囲んであげる。

        $owners = Owner::select("id", "name", "email", "created_at")->get();
        return view("admin.owners.index", compact("owners"));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //view()ってのはメソッドではなく、ヘルパ関数である
        return view("admin.owners.create");
        //(viewsフォルダ内の)「adminフォルダのownersフォルダのcreate.blade.phpを表示」って意味
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    // Request $request:[メソッドインジェクション]
    //フォームで入力された値をRequestクラスとして引数で受けとっている
    public function store(Request $request)
    {
        //まずバリデーションかけて
        $request->validate([//AdminsTableとやりとり
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            //ユニークキー:admins(adminsモデルてこと)
            //したのconfirmedの前にStringいる？わからん、つけとくことにしよう。バリデーションだし。
            //confirmedをつけることで二つの入力されたパスワードを
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        //ここで保存を実行
        Owner::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        //sessionメッセージ「トースト」も
        return redirect()
        ->route("admin.owners.index")
        ->with("message", "オーナー登録を実施しました");
        //登録された後、admin.owners.index(一覧画面)にリダイレクトがかかる。
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
        $owner = Owner::findOrFail($id);
        return view("admin.owners.edit", compact("owner"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //edit.blade.phpでフォームに入力された値が$requestに入る。
    public function update(Request $request, $id)
    {
        //Ownerモデルでidを指定した情報をインスタンス化している
        $owner = Owner::findOrFail($id);
        //$ownerオブジェクトのnameに$requestオブジェクトのnameを入れる。
        $owner->name = $request->name;
        $owner->email = $request->email;
        $owner->password = Hash::make($request->password);//暗号化するためにHashメソッドを使う
        $owner->save();//保存処理

        //保存できたら、indexへ戻すためのリダイレクトを記述。[更新しました]メッセージも出す
        return redirect()
        ->route("admin.owners.index")
        ->with("message", "オーナー情報を更新しました");
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
