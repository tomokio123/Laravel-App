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
use App\Http\Requests\ProductRequest;//update・storeメソッド両方ともProductRequestに型を変更する
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
    public function store(ProductRequest $request)
    //バリデーションをProductRequestに記述しそれが通過したらProductRequestで返り値が来るようにしたので
    //ProductRequestとしておく
    {
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

    public function edit($id)
    {
        //商品ID取得
        $product = Product::findOrFail($id); //idで商品IDを指定する
        //在庫情報取得
        $quantity = Stock::where('product_id', $product->id)
        ->sum('quantity');

        //外部キー取得
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

        // dd($product->image1); => 1
        //dd($product->imageFirst);=>  "filename" => "public/products/sample1.png"
        //編集画面に渡す
        return view("owner.products.edit", compact("product", "quantity", "shops", "images", "categories"));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        //dd($request);
        //ProductRequest(フォームリクエストでvalidationをかけつつ、さらに数量にバリデーションをかける)
        $request->validate([  
            'current_quantity' => ['required', 'integer'],
        ]);

        $product = Product::findOrFail($id);//1つの商品IDを取得
        $quantity = Stock::where('product_id', $product->id)//productIDの在庫合計を取得
        ->sum('quantity');

        //edit画面で取得した在庫量とupdateで読み込んだ際に取得した在庫の数が異なっていれば戻す
        //ルートパラメータの値を取得する必要がある
        if($request->current_quantity !== $quantity){
            //ルートパラメータを取得することができる
            $id = $request->route()->parameter("product");
            //ルートパラメータを持った状態で戻すことができる
            return redirect()->route('owner.products.edit', ['product' => $id])
            ->with([
                "message" => "在庫数が変更されています。再度確認してください",
                "status" => "alert"]
            );
        } else {
             //保存処理は商品と在庫をまとめて登録したいので、transactionをかける。
             try{
                DB::transaction(function () use($request, $product) {
                    //$product->name(現在の商品情報たち) = $request->name(更新ボタンのrequestから送られてきた情報);
                    //とすることで情報の上書きをしている
                        $product->name = $request->name;
                        $product->information = $request->information;
                        $product->price = $request->price;
                        $product->sort_order = $request->sort_order;
                        $product->shop_id = $request->shop_id;
                        $product->secondary_category_id = $request->category;
                        $product->image1 = $request->image1;
                        $product->image2 = $request->image2;
                        $product->image3 = $request->image3;
                        $product->image4 = $request->image4;
                        $product->is_selling = $request->is_selling;
                        $product->save();//DBに繋いで保存を実行するにはsaveメソッドいる

                    //1:追加
                    if($request->type === "1"){
                        $newQuantity = $request->quantity;
                    }
                    //2:削減(-)
                    if($request->type === "2"){
                        $newQuantity = $request->quantity * -1;
                    }

                    Stock::create([
                        'product_id' => $product->id,
                        'type' => $request->type,
                        'quantity' => $newQuantity
                    ]);
                }, 2);
            }catch(Throwable $e){
                Log::error($e);
                throw $e;
            }
            

            return redirect()
            ->route("owner.products.index")
            ->with(
                ["message" => "商品情報を更新しました",
                "status" => "info"]//この「status」をindex.blade.phpの[flash-message]の[status属性]に渡す
            );
        }
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
