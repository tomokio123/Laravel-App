<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:users");//オーナーログインに限る

        //$this->middleware(function ($request, $next) {
        //    //
        //    $id = $request->route()->parameter("product");
        //    if(!is_null($id)){//null判定
        //        //product内にはOWnerは紐づいていないので一旦shopに行ってからowner特定してID取得する
        //        $productsOwnerId = Product::findOrFail($id)->shop->owner->id;
        //        $productId = (int)$productsOwnerId; //キャストして文字列を数字にした。
        //        if($productId  !== Auth::id()){//同じでなかったら
        //            abort(404); //404画面表示
        //        }
        //    }

            //return $next($request);
        //};
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
