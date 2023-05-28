<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use App\Models\Shop;
use App\Models\PrimaryCategory;
use App\Models\Owner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:owners");//オーナーログインに限る

        $this->middleware(function ($request, $next) {
            //
            $id = $request->route()->parameter("product");
            if(!is_null($id)){//null判定
                //product内にはOWnerは紐づいていないので一旦shopに行ってからowner特定してID取得する
                $productsOwnerId = Product::findOrFail($id)->shop->owner->id;
                $productId = (int)$productsOwnerId; //キャストして文字列を数字にした。
                if($productId  !== Auth::id()){//同じでなかったら
                    abort(404); //404画面表示
                }
            }

            return $next($request);
        });
    }

    //product一覧を表示する
    public function index()
    {
        //imageFirst = Productの[image1]をそう定義しているだけ、別名。
        //$products = Owner::findOrFail(Auth::id())->shop->product;
        //withで「EagerLord」処理。引数にはリレーションを書く。リレーションのリレーションを引っ張ってくるには.で繋げることができる
        $ownerInfo = Owner::with("shop.product.imageFirst")//Owner一人に紐づく「たくさんの店」が持つ「たくさんの商品」のimage1のID
        ->where('id', Auth::id())->get();
        //dd($ownerInfo);
        return view("owner.products.index", compact("ownerInfo"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $shops = Shop::where('owner_id', Auth::id())
        ->select('id', 'name')
        ->get();
        $images = Image::where('owner_id', Auth::id())
        ->select('id', 'title', 'filename')
        ->orderby('updated_at', 'desc')->get();
        //リレーション先の情報を取ってくるときはN+1問題→Eager Loadingを行え
        //PrimaryCategoryモデル内のsecondary()のこと↓
        $categories = PrimaryCategory::with('secondary')
        ->get();

        return view('owner.products.create', compact('shops', 'images', 'categories'))->render();
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
        dd($request);
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
