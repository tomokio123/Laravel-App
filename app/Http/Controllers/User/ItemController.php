<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:users");//オーナーログインに限る

        $this->middleware(function ($request, $next) {
            //販売されていない商品の閲覧防止のために以下も記述
            //リクエスト情報からルートの「ルートパラメータ」["item"]を参照する。
            $id = $request->route()->parameter("item");//ルートパラメーター->web.phpの"show/{item}"の部分
            //そのルートパラメータがnullでない時のみ処理。
            if(!is_null($id)){//null判定。itemが渡ってきたライカの処理。そのidで販売停止の物等のチェックをかけたい
                //availableItemsが存在するかどうかを確かめるのでProduct::availableItems()から引っ張る
                //getでもいいが、where()の条件に当てはまるのが存在するかどうかを確かめれる(true/falseを返せる)existsを使う
                //"products.id"がルートパラメータと$idが等しくなるものが存在していればtrueを返す
                $itemId = Product::availableItems()->where("products.id", $id)->exists();
                if(!$itemId){//$itemIdが存在していなかったら
                    abort(404); //404画面表示
                }
            }

            return $next($request);
        });
    }

    public function index()
    {
        //スコープにまとめた()
        $products = Product::availableItems()->get();

        //resource/viewsの中のuser/index.blade.phpのこと
        return view('user.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        //在庫量(の合計)取得
        $quantity = Stock::where('product_id', $product->id)
        ->sum('quantity');

        if($quantity > 9){
            $quantity = 9;
        }

        //dd($product->shop->filename); //"public/shops/sample1.png"
        //dd($product->imageFirst->filename); //"public/products/sample5.png"

        return view("user.show", compact("product", "quantity"));
    }
}
