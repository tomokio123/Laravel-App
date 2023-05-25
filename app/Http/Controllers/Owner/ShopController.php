<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Shop;//shopモデルもimportしてあげよう
use Illuminate\Http\Request;

//ログインしているownerのIDを取得するためにAuthファサードを使いたいのでこれを読み込む
use Illuminate\Support\Facades\Auth;
//ストレージフォルダでimageのアップロードなどを扱いたいので以下を読み込む
use Illuminate\Support\Facades\Storage;
//use InterventionImage;
use App\Http\Requests\UploadImageRequest;

class ShopController extends Controller
{
    //shopはOwnerに紐づいているのでownersでガードをかける。
    public function __construct()
    {
        $this->middleware("auth:owners");
        $this->middleware(function ($request, $next) {
            //
            $id = $request->route()->parameter("shop");
            if(!is_null($id)){//null判定
                $shopsOwnerId = Shop::findOrFail($id)->owner->id;
                $shopId = (int)$shopsOwnerId; //キャストして文字列を数字にした。
                $ownerId = Auth::id();
                if($shopId !== $ownerId){//同じでなかったら
                    abort(404); //404画面表示
                }
            }

            return $next($request);
        });
    }

    public function index()//一覧画面に遷移する時のメソッド
    {
        //shopのindexに移動するときには店一覧が見たいのでDBから検索して表示する機能が以下の
        //[$shops]変数を定義している箇所である。

        //use Illuminate\Support\Facades\Auth を上で読み込め。
        //Auth::id();とすることでログインしているownerのIDを取得できる
        //取得したownerのIDを使ってShopモデルにある"owner_id"カラムを検索する。
        //検索した値と同じものがあれば->getする。
        $shops = Shop::where("owner_id", Auth::id())->get();

        //オーナーに紐づく1個以上のShopsデータ($shops)をviewに渡す
        return view("owner.shops.index", compact("shops"));
    }

    public function edit($id) //shop編集ページに遷移する時のメソッド
    {
        //(移動するだけなのでid、に紐づいた1shopの情報だけ用意すれば良い)
        $shop = Shop::findOrFail($id); 
        // Model名::find()は、一致するidが見つからなかった場合は、「null」を返します。
        // Model名::findOrFail()は、一致するidが見つからなかった場合は、「エラー」を返します。
        //→findOrFail($id)は「一カラムを($idを主キーとして)まとめて取りだしたいとき」に使う
        //逆にidnexメソッド内では「その店舗id一覧を取り出し、かつそれらのIdに紐づく細かい情報も取り出したいとき」であるので、
        //より「抽象化」した変数である「$shops」を定義している

        return view("owner.shops.edit", compact("shop"));
        //compactで渡すときは変数から$を抜いたものに""をつける

    }

    public function update(UploadImageRequest $request, $id)
    //編集ページにて「更新する」を押したときのメソッド
    {
        //まず3つにバリデーションかけて
        $request->validate([    //ShopsTableとやりとり
            'name' => ['required', 'string', 'max:50'],
            'information' => ['required', 'string', 'max:1000'],
            'is_selling' => 'required'
        ]);

        $imageFile = $request->image;//リクエストのimageを変数に入れて
        //null判定かつ、それがアップロードできているか(isValid)判定する
        if(!is_null($imageFile) && $imageFile->isValid()){
            $fileNameToStore = Storage::putFile("public/shops", $imageFile); //リサイズなしの場合
            //putFileメソッドは「storage/appフォルダ内にあるpublicフォルダ内にshopフォルダがあればそこに(無ければ作成し)、
            //ファイル名も自動生成して保存してあげる」といくメソッド。第二引数には渡すimageが格納された変数を配置する
        }

        //Shopモデルでidを指定した情報をインスタンス化している(オブジェクト)
        $shop = Shop::findOrFail($id);
        //requestで取ってきた各情報を「$shop」変数に格納し、
        //return redirect()後のroute先に渡す
        //$shopオブジェクトのnameに$requestオブジェクトのnameを入れる。
        $shop->name = $request->name; 
        $shop->information = $request->information;
        $shop->is_selling = $request->is_selling;
        if(!is_null($imageFile) && $imageFile->isValid()){
            $shop->filename = $fileNameToStore;
        }

        $shop->save();//保存

        //リダイレクト先はindex(一覧)ページ
        return redirect()
        ->route("owner.shops.index")
        ->with(['message' => '店舗情報を更新しました。',
                'status' => 'info' ]);//views/owner/shops/index.bladeにフラッシュメッセージの表示を書く
    }
}