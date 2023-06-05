<?php

namespace App\Models;

use App\Consts\PrefectureConst;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop;
use App\Models\SecondaryCategory;
use App\Models\Image;
use App\Models\User;
use App\Models\Stock;
//DBファサード
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'name',
        'information',
        'price',
        'is_selling',
        'sort_order',
        'secondary_category_id',
        'image1',
        'image2',
        'image3',
        'image4',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function category()
    {
        return $this->belongsTo(SecondaryCategory::class, 'secondary_category_id');
    }

    //$image1という商品Modelのプロパティを、リレーションでは「imageFirst()」と呼ぶことにする。
    public function imageFirst()
    {
        return $this->belongsTo(Image::class, 'image1', 'id');
    }
    public function imageSecond()
    {
        return $this->belongsTo(Image::class, 'image2', 'id');
    }
    public function imageThird()
    {
        return $this->belongsTo(Image::class, 'image3', 'id');
    }
    public function imageFourth()
    {
        return $this->belongsTo(Image::class, 'image4', 'id');
    }
    
    //１つの商品に対して「たくさんの在庫」を持つ
    public function stock()
    {
        return $this->hasMany(Stock::class);
    }

    //
    public function users()
    {
        //多対多(Product<=>User)の関係を定義。belongsToManyをProduct/Userの両方に書く
        return $this->belongsToMany(User::class, 'carts')//中間テーブル="carts"とする
        ->withPivot(['id', 'quantity']);//多対多実現のために中間テーブル(Pivot)を紐づける
    }
    //Scopeを定義,Scopeは引数/returnに$queryを用いることが約束で、名前の最初はscopeで始める。
    public function scopeAvailableItems($query)
    {
        //商品IDごとにまとめた在庫の表示をしたい(selectで選んだカラムしかgroupByできないと思う)
        $stocks = DB::table('t_stocks')
        //サブクエリを使うときは::rawを使う
        ->select('product_id', DB::raw('sum(quantity) as quantity'))//商品ID,在庫合計の順に表示し、
        //whereだとgroupByする前に条件指定が入るのでgroupByを優先したいときはhavingを使う
        ->groupBy('product_id')->having('quantity', '>=', 1);//在庫が1以上の範囲とする・[150]のビデオ

        return $query->joinSub($stocks, 'stock', function($join){
            $join->on('products.id', '=', 'stock.product_id');
        })
        ->join('shops', 'products.shop_id', '=', 'shops.id')
        ->join('secondary_categories', 'products.secondary_category_id', '=', 'secondary_categories.id')
        ->join('images as image1', 'products.image1', '=', 'image1.id')
        //商品一覧はimage1だけで足りているのでimage1だけ記述
        ->where('shops.is_selling', true)
        ->where('products.is_selling', true)
        ->select('products.id as id', 'products.name as name', 'products.price'
        ,'products.sort_order as sort_order'
        ,'products.information', 'secondary_categories.name as category'
        ,'image1.filename as filename');
    }

    public function scopeSortOrder($query, $sortOrder)
    {
        //渡ってくるsort_orderがnull・recommendの場合はsort_order順(デフォルトのsort_order)に並べる
        if($sortOrder === null || $sortOrder === PrefectureConst::SORT_ORDER["recommend"]){
            return $query->orderBy("sort_order", "asc");
        }
        if($sortOrder === PrefectureConst::SORT_ORDER["higherPrice"]){
            return $query->orderBy("price", "desc");
        }
        if($sortOrder === PrefectureConst::SORT_ORDER["lowerPrice"]){
            return $query->orderBy("price", "asc");
        }
        if($sortOrder === PrefectureConst::SORT_ORDER["later"]){
            return $query->orderBy("products.created_at", "desc");//productsテーブルのcreated_at順に並べる
        }//この辺の呼び出し名は上のscopeAvailableItemsで呼び出した時のasやネーミングによる。
        if($sortOrder === PrefectureConst::SORT_ORDER["older"]){
            return $query->orderBy("products.created_at", "asc");
        }
    }
}
