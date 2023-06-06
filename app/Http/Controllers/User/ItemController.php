<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Stock;
use App\Models\PrimaryCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;//メールファサード読み込まないと送信できない
use App\Mail\TestMail; //TestMailをインポート
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendThanksMail;//ありがとうメール

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

    public function index(Request $request)
    {
        //同期
        //Mail::to("abeazpon@gmail.com")//Mail::toメソッドで送信先を指定し、
        //->send(new TestMail());//send()メソッドで送信(と送信の内容を記述しているクラスを指定)

        //非同期に送信 dispatch='発火'のいみ
        SendThanksMail::dispatch();
        //最後にphp artisan queue:work をするとワーカーが起動してキューに溜まっていたジョブたちが処理されてメールとして送信される
        //普通ならここで常にワーカーを働かせるために「superviser」などを使うが、割愛している


        //スコープにまとめた
        $products = Product::availableItems()
        ->selectCategory($request->category ?? "0")//nullならデフォルトカテゴリ(0="recommend")
        ->searchKeyword($request->keyword)//検索の場合はここでnull判定はしない
        ->sortOrder($request->sort)
        ->paginate($request->pagination ?? "20");//??(nullチェックしてnullなら20をデフォにした)
        //->getではなく->とすることでgetする且つ、ページネーションも可能にする
        //available(scope)で販売可能商品を判定し->sortOrder($request->sort)でsort順の指定値を渡す

        //リレーション先の情報を取ってくるときはN+1問題→Eager Loadingを行え
        //PrimaryCategoryモデル内のsecondary()のこと↓
        $categories = PrimaryCategory::with('secondary')
        ->get();

        //resource/viewsの中のuser/index.blade.phpのこと
        return view('user.index', compact('products', 'categories'));
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
