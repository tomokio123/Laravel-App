<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
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

    public function index(){

        //stocksテーブルを(productIDごとに)グループ化→数量が1以上
        $stocks = DB::table('t_stocks')
        //サブクエリを使うときは::rawを使う
        ->select('product_id', DB::raw('sum(quantity) as quantity'))//商品ID,在庫合計の順に表示し、
        //whereだとgroupByする前に条件指定が入るのでgroupByを優先したいときはhavingを使う
        ->groupBy('product_id')->having('quantity', '>', 1);//商品IDごとにまとめた在庫の表示に成功

        $products = DB::table('products')//エロくあんとが使えないのでリレーションを引き直す。
        ->join("secondary_categories", 'products.secondary_category_id', '=', 'secondary_categories.id')
        //第二カテゴリテーブルのsecondary_categoriesのidとproductsのsecondary_category_idをjoin
        //同じように商品テーブルのimageとimagesテーブルのidを結合する。またimageは4つあるのでimage as[]とすることで重複を避けている
        ->join("images as image1", "products.image1", '=', "image1.id")
        ->join("images as image2", "products.image2", '=', "image2.id")
        ->join("images as image3", "products.image3", '=', "image3.id")
        ->join("images as image4", "products.image4", '=', "image4.id")
        //joinSub(サブクエリ,サブクエリのテーブル名,表示する列の情報)
        ->joinSub($stocks, "stock", function($join){
            //productsテーブルのidと、stockテーブルのproduct_idを結合させる
            $join->on('products.id', '=', 'stock.product_id');
        }) //商品と店を紐づける。join(結合するテーブル名, productsテーブルのshop_id, と,stockテーブルのproduct_id を結合させる)
        ->join('shops', 'products.shop_id', '=', 'shop_id')
        //結合し終わったら、↑店舗テーブルでのis_sellingの真偽と↓商品テーブルでのis_sellingの真偽を検査してどっちもセーフなやつだけgetする
        ->where('shops.is_selling', true)
        ->where('products.is_selling', true)
        //select()では、結合後はnameだけじゃどのテーブルのnameか？がわからなくなるのでproducts.nameなどと指定をかけないといけない
        ->select("products.id", "products.name as name", "products.price", "products.sort_order",
        'products.information', 'secondary_categories.name as category', 
        'image1.filename as filename')//上でimage1はimageテーブルの別名としていて、
        //image1のfilenameをfilenameとして用いる
        ->get();//ここでselectした別名でviewで使うことになる($products->)
        //dd($stocks, $products);

        //resource/viewsの中のuser/index.blade.phpのこと
        return view('user.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        return view("user.show", compact("product"));
    }
}
