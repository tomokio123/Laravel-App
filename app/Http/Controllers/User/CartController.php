<?php

namespace App\Http\Controllers\User;

//require_once 'vendor/autoload.php';
//require_once '/vendor/to/stripe-php/init.php';
require_once '../vendor/autoload.php';
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
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
        foreach($products as $product){
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

        \Stripe\Stripe::setApiKey('sk_test_xxx');
        //dd($lineItems);
        //\Stripe\Stripe::setApiKey(config('stripe.stripe_secret_key'));
        $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET_KEY"));
        // \Stripe\StripeClient::setApiKey(env("STRIPE_SECRET_KEY"));//秘密鍵設定
        $session = $stripe->checkout->sessions->create([
            "line_items" => [$lineItems],
            "mode" => "payment",
            "success_url" => route("user.items.index"),
            "cancel_url" => route("user.cart.index"),
            'payment_method_types' => ['card'],
        ]);

        $publicKey = env("STRIPE_PUBLIC_KEY");

        echo $session;

        return view("user.checkout", compact("session", "publicKey"));

        //$stripe->checkout->sessions->create
    }
}
