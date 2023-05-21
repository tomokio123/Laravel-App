<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Shop;//shopモデルもimportしてあげよう
use Illuminate\Http\Request;


//ログインしているownerのIDを取得するためにAuthファサードを使いたいのでこれを読み込む
use Illuminate\Support\Facades\Auth;

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
        $ownerId = Auth::id();//ログインしているownerのIDを取得
        //取得したowner Idを使ってShopモデルを検索する。
        //"owner_id"カラムに$ownerIdに入ってきた値と同じものがあれば->getする。
        $shops = Shop::where("owner_id", $ownerId)->get();

        //オーナーに紐づく1個以上のShopsデータ($shops)をviewに渡す
        return view("owner.shops.index", compact("shops"));
    }

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {

    }
}
