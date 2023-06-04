<?php

namespace App\Http\Controllers\User;

//require_once 'vendor/autoload.php';
//require_once '/vendor/to/stripe-php/init.php';
require_once '../vendor/autoload.php';
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Consts\PrefectureConst;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    //
    public function add(Request $request)
    {
        $itemInCart = Cart::where('product_id', $request->product_id)
        //Cartテーブルのproduct_idが$requestで渡ってくるproduct_idと等しいとき、かつ
        ->where('user_id', Auth::id())//Cartテーブルのuser_idと今ログインしているidが等しいとき
        ->first();//firstで「一件だけ」取得する

        if($itemInCart){//itemInCartに値が入っているとき
            //左辺が現在のカートの中の個数 += リクエストで増減値が送られてくるので右辺でそれを反映
            $itemInCart->quantity += $request->quantity;
            $itemInCart->save();//saveとしないと保存されない
        } else {//itemInCartに値が入っていないとき,単にCartテーブルの中に一致する項目が何もないわけやから新規作成(create)すれば良い
            Cart::create([
                "user_id" => Auth::id(),
                "product_id" => $request->product_id,
                "quantity" => $request->quantity
            ]);
        }

        return redirect()->route("user.cart.index");
    }

    public function index()
    {
        //dd(PrefectureConst::LIST["reduce"]);
        $user = User::findOrFail(Auth::id());//Auth::idでログインしているUser情報取得
        $products = $user->products;//userに紐づくproductsを取得
        $totalPrice = 0;

        foreach($products as $product){
            //「$productのprice」に「$productに紐づく中間テーブル(cartsテーブル)のquantity(カート量)」をかける
            //それをtotalPriceに上乗せ(+=)する
            $totalPrice += $product->price * $product->pivot->quantity;
        }

        //dd($products, $totalPrice);

        //$this->checkout();
        return view('user.cart', compact("products", "totalPrice"));
    }

    public function delete($id)
    //この$idは「商品ID」を想定。$idにはルートパラメータ{item}として渡ってくるように定義した。
    {
        Cart::where("product_id", $id)//送られてくるidと商品IDが同じで、かつ、
        ->where("user_id", Auth::id())//Cartテーブルのuser_idと現在ログインしてるIDが等しいデータを取得し、
        ->delete();//delete

        //リダイレクトはcart.index
        return redirect()->route("user.cart.index");
    }

    public function checkout()
    {
        $user = User::findOrFail(Auth::id());//Auth::idでログインしているUser情報取得
        $products = $user->products;//userに紐づくproductsを取得

        $lineItems = [];//カートに入っている情報を格納する配列を用意
        //在庫を確認し、決済前に在庫を減らしておく=>決済前に在庫を確認し、
        foreach($products as $product){
            $quantity = "";
            //「決済前」の在庫を確認して保持しておく
            $quantity = Stock::where("product_id", $product->id)->sum("quantity");

            //Cartの量より「決済前在庫」が多かったらもう買えないのでindexへ強制送還する
            if($product->pivot->quantity > $quantity){
                //ここはreturn viewではない。indexメソッド内の変数もviewに渡す必要があるため
                return redirect()->route("user.cart.index");
            } else {
                //在庫が余裕あったらlineItemに情報を格納していく
                $lineItem = [
                    "quantity" => $product->pivot->quantity,
                    "price_data" => [
                        "unit_amount" => $product->price,
                        "currency" => "jpy",
                        "product_data" => [
                            "name" => $product->name,
                            "description" => $product->information
                        ],
                    ],
                ];
                array_push($lineItems, $lineItem);//配列に追加
                //array_push(追加先の配列, 追加する値)
            }

        }

        //\Stripe\Stripe::setApiKey('sk_test_xxx');
        //dd($lineItems);
        //\Stripe\Stripe::setApiKey(config('stripe.stripe_secret_key'));
        $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET_KEY"));
        // \Stripe\StripeClient::setApiKey(env("STRIPE_SECRET_KEY"));//秘密鍵設定
        $session = $stripe->checkout->sessions->create([
            "line_items" => [$lineItems],
            "mode" => "payment",
            "success_url" => route("user.cart.success"),//successメソッド呼び出す
            "cancel_url" => route("user.cart.cancel"),//cancelメソッド呼び出す
            'payment_method_types' => ['card'],
        ]);

        //Stripeで決済する前に先にStockテーブルの在庫を減らしておく
        foreach($products as $product){
            Stock::create([
                'product_id' => $product->id,
                'type' => PrefectureConst::LIST["reduce"],//2
                'quantity' => $product->pivot->quantity * -1,//変化量。
            ]);
        }
        
        $publicKey = env("STRIPE_PUBLIC_KEY");

        //echo $session;

        return view("user.checkout", compact("session", "publicKey"));

        //$stripe->checkout->sessions->create
    }

    public function success()
    {
        Cart::where("user_id", Auth::id())->delete();//カートの情報を消す
        return redirect()->route("user.items.index");//商品一覧に戻す
    }

    public function cancel()
    {
        $user = User::findOrFail(Auth::id());//ユーザ情報取得

        foreach($user->products as $product){
            Stock::create([
                'product_id' => $product->id,
                'type' => PrefectureConst::LIST["add"],//1(+)
                'quantity' => $product->pivot->quantity,
                //(+の)変化量。カートから購入する直前に減らしていたが、キャンセルされたので戻してあげる処理
            ]);
        }

        return redirect()->route("user.cart.index");//カートに戻す
    }
}
