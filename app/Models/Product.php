<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop;
use App\Models\SecondaryCategory;
use App\Models\Image;
use App\Models\Stock;

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

    //$image1という商品Modelのプロパティを「imageFirst()」とリレーションにおいては呼ぶことにする。
    public function imageFirst()
    {
        return $this->belongsTo(Image::class, 'image1', 'id');
    }
    
    //１つの商品に対して「たくさんの在庫」を持つ
    public function stock()
    {
        return $this->hasMany(Stock::class);
    }
}
