<?php
namespace App\Services;
use App\Models\Product;
use App\Models\Cart;

class CartService {

  // カートの中にある複数商品をもらって($itemとして)、
  public static function getItemsInCart($items)
  {
    //新しく配列を作って返す
    $products = [];

    
    foreach($items as $item){
      //$itemにproduct_idが含まれているのでそれを使ってfindOrFailで商品を特定し取得する
        $p = Product::findOrFail($item->product_id);
        //オーナー情報が欲しい→その商品に結びつくオーナー情報をリレーションを用いて取得
        $owner = $p->shop->owner;
        $ownerInfo = [//キーが「name」でかぶるので「ownerName」とキーの名前を変更した、オーナー情報を保持する配列を作る
          'ownerName' => $owner->name,
          'email' => $owner->email
        ];

        //dd($ownerInfo);OK

        //商品情報を取得し配列にする。渡ってきたitemから商品IDを特定する
        $product = Product::where('id', $item->product_id)
        ->select('id', 'name', 'price')->get()->toArray();

        //カートの数量情報を取得し配列にする。渡ってきたitemから商品IDを特定する
        $quantity = Cart::where('product_id', $item->product_id)
        ->select('quantity')->get()->toArray();
       //以上の二つは以下で配列に加えるためにtoArrayにした。 しかし配列には一個しかないので[0]ばんめと場所を指定してあげる
       //配列がただのListなら値ぶち込むだけでよかったが、Map型(固有のキーがいる)なので、このような形になってる
        //dd($ownerInfo, $product, $quantity);
        //上記の配列をもう一つの別のの配列に結合。
        $result = array_merge($product[0], $ownerInfo, $quantity[0]);
        
        //元々作っていた$products配列に上記の配列をくっつける
        array_push($products, $result);

    }
    dd($products);
    return $products;
  }
}
