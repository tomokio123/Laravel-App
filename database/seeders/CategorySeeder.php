<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
//DBファサードを利用可能にする
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table("primary_categories")->insert([
            [
                "name" => "キッズファッション",
                "sort_order" => 1 ,
            ],
            [
                "name" => "出産祝い・ギフト",
                "sort_order" => 2 ,
            ],
            [
                "name" => "ベビーカー",
                "sort_order" => 3 ,
            ],
        ]);

        DB::table("secondary_categories")->insert([
            //primary_category_id:1
            [
                "name" => "靴",
                "sort_order" => 1 ,
                "primary_category_id" => 1 ,
            ],
            [
                "name" => "トップス",
                "sort_order" => 2 ,
                "primary_category_id" => 1 ,
            ],
            [
                "name" => "ランドセル・バッグ",
                "sort_order" => 3 ,
                "primary_category_id" => 1 ,
            ],
            //primary_category_id:2
            [
                "name" => "ギフトセット",
                "sort_order" => 1 ,
                "primary_category_id" => 2 ,
            ],
            [
                "name" => "記念品",
                "sort_order" => 1 ,
                "primary_category_id" => 2 ,
            ],
            [
                "name" => "おむつ",
                "sort_order" => 1 ,
                "primary_category_id" => 2 ,
            ],
        ]);
    }
}
