<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockFactory extends Factory
{
    protected $model = Stock::class;
    
    public function definition()
    {
        return [
            //Stockは外部キーとして商品IDを持つ。
            //ProductFactoryに応じたダミーデータを作れるように以下のようにしている
            //Modelでuse hasFactoryとuse \Factories\HasFactory ...のあたりを書いているからできること。
            'product_id' => Product::factory(),//product登録順にここも登録される
            'type' => $this->faker->numberBetween(1,2),
            'quantity' => $this->faker->randomNumber,
        ];
    }
}
