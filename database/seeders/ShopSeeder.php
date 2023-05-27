<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
//ファサードを利用可能にする
use Illuminate\Support\Facades\DB;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //以下が書けたら->DatabeseSeeder.phpにcallメソッド内でShopSeeder::classを追記し、
        //後はphp artisan migrate:fresh --seed でOK
        DB::table("shops")->insert([
            [
                'owner_id' => 1,
                'name' => 'ここに店名が入ります',
                'information' => 'ここにお店の情報が入ります。ここにお店の情報が入ります。ここにお店の情報が入ります。',
                'filename' => 'public/shops/sample1.png',
                'is_selling' => true    
            ],
            [
                //idは自動で生成される
                "owner_id" => 2,
                "name" => "店名情報が入ります...",
                "information" => "information:お店情報-information:お店情報-information:お店情報...",
                "filename" => "" ,
                "is_selling" => true
            ],
            [
                "owner_id" => 3,
                "name" => "店名情報が入ります...",
                "information" => "information:お店情報-information:お店情報-information:お店情報...",
                "filename" => "public/shops/sample1.png" ,
                "is_selling" => true
            ],
        ]);
    }
}
