<?php

namespace App\Http\Controllers\User;

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
}
