<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Product;
use Throwable;
use Illuminate\Support\Facades\Log;//Logのファサードがないと例外処理でファサードが使えない
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; //QueryBuidler クエリビルダー
use App\Models\Shop;
use App\Models\Stock;
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
        //$request->validate([//AdminsTableとやりとり
        //    //''などの「キー」はview側から入ってくるname属性
        //    'name' => ['required', 'string', 'max:255'],
        //    'information' => ['required', 'string', 'max:1000'],
        //    'price' => ['required', 'integer'],
        //    'sort_order' => ["nullable", 'integer'],
        //    'quantity' => ['required', 'integer'],
        //    //exits:shop_idが存在しているかどうかの確認。=>[exits:shops,id]//shopsと書いている場所にはtable名を書いている
        //    'shop_id' => ['required', 'exits:shops,id'],
        //    'category' => ['required', 'exits:secondary_categories,id'],
        //    'image1' => ['nullable', 'exits:images,id'],
        //    'image2' => ['nullable', 'exits:images,id'],
        //    'image3' => ['nullable', 'exits:images,id'],
        //    'image4' => ['nullable', 'exits:images,id'],
        //    'is_selling' => ['required'],
        //]);

        //保存処理は商品と在庫をまとめて登録したいので、transactionをかける。
        try{
            DB::transaction(function() use($request){
                $product = Product::create([
                    'name' => $request->name,
                    'information' => $request->information,
                    'price' => $request->price,
                    'sort_order' => $request->sort_order,
                    'shop_id' => $request->shop_id,
                    //view側のname($request->)としてはcategoryだが、テーブルとしてはsecondary_category_idであるので左右不一致
                    'secondary_category_id' => $request->category,
                    'image1' => $request->image1,
                    'image2' => $request->image2,
                    'image3' => $request->image3,
                    'image4' => $request->image4,
                    'is_selling' => $request->is_selling,
                ]);

                //同時にストックも保存したい
                Stock::create([
                    'product_id' => $product->id,//外部キー。上記で登録した個々の商品Idのこと
                    'type' => 1, //入庫在庫を増やす場合には[1]とする
                    'quantity' => $request->quantity //quantityは$requestから入ってくる
                ]);

            }, 2); //第二引数に2を入れることで「2回繰り返す」の意味になる
        }catch(Throwable $e){
            Log::error($e);
            throw $e;
        }

        return redirect()
        ->route("owner.products.index")
        ->with(
            ["message" => "商品を登録しました",
            "status" => "info"]//この「status」をindex.blade.phpの[flash-message]の[status属性]に渡す
        );
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
