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

    public function index()
    {
        //use Illuminate\Support\Facades\Auth を上で読み込め。
        //$ownerId = Auth::id();//ログインしているownerのIDを取得->$shopsの行に1行にまとめた
        //取得したowner Idを使ってShopモデルを検索する。
        //"owner_id"カラムに$ownerIdに入ってきた値と同じものがあれば->getする。
        $shops = Shop::where("owner_id", Auth::id())->get();

        //オーナーに紐づく1個以上のShopsデータ($shops)をviewに渡す
        return view("owner.shops.index", compact("shops"));
    }

    public function edit($id)
    {
        $shop = Shop::findOrFail($id); 

        return view("owner.shops.edit", compact("shop"));
        //compactで渡すときは変数から$を抜いたものに""をつける

    }

    public function update(UploadImageRequest $request, $id)
    {
        $imageFile = $request->image;//リクエストのimageを変数に入れて
        //null判定かつ、それがアップロードできているか(isValid)判定する
        if(!is_null($imageFile) && $imageFile->isValid()){
            Storage::putFile("public/shops", $imageFile); //リサイズなしの場合
            //Storage::putFileAs('public/' . '/', $file, $fileNameTo);
            //putFileメソッドは「storage/appフォルダ内にあるpublicフォルダ内にshopフォルダがあればそこに(無ければ作成し)、
            //ファイル名も自動生成して保存してあげる」といくメソッド。第二引数には渡すimageが格納された変数を配置する
            //$fileName = uniqid(rand().'_');
            //$extension = $imageFile->extension();
            //$fileNameToStore = $fileName . '.' . $extension;

            //$resizedImage = InterventionImage::make($imageFile)->resize(1920, 1080)->encode();


            //Storage::put('public/shops' . $fileNameToStore, $resizedImage);
        }

        return redirect()->route("owner.shops.index");
    }
}