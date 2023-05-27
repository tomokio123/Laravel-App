<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
//DBファサードを利用可能にする
use Illuminate\Support\Facades\DB;

//DatabaseSeederの中にIamgeSeeder追加せよ
class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table("images")->insert([
            [
                //idは自動で生成される
                "owner_id" => 1,
                "filename" => "public/products/sample1.png",
                "title" => null,
            ],
            [
                "owner_id" => 1,
                "filename" => "public/products/sample2.png",
                "title" => null,
            ],
            [
                "owner_id" => 1,
                "filename" => "public/products/sample3.png",
                "title" => null,
            ],
            [
                "owner_id" => 1,
                "filename" => "public/products/sample4.png",
                "title" => null,
            ],
            [
                "owner_id" => 1,
                "filename" => "public/products/sample5.png",
                "title" => null,
            ],
            [
                "owner_id" => 1,
                "filename" => "public/products/sample6.png",
                "title" => null,
            ],
        ]);
    }
}
