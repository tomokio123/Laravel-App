<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
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
        dd("TEST");
    }
}
