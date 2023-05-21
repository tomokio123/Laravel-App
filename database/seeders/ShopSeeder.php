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
                //idは自動で生成される
                "owner_id" => 1,
                "name" => "店名情報が入ります...",
                "information" => "information:お店情報-information:お店情報-information:お店情報...",
                "filename" => "" ,
                "is_selling" => true
            ],
            [
                "owner_id" => 2,
                "name" => "店名情報が入ります...",
                "information" => "information:お店情報-information:お店情報-information:お店情報...",
                "filename" => "" ,
                "is_selling" => true
            ],
        ]);
    }
}
