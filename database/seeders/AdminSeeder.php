<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
//ファサードを利用可能にする
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table("admins")->insert(
        [    //recreateしたmigrationファイルを参考にしよう
            "name" => "yuzu",
            "email" => "yuzu@gmail.com",
            "password" => Hash::make("yuzuman1307"),//passwordは暗号化を書けないとだめ
            "created_at" => "2023/01/01 11:11:11"
        ],
    );
    }
}
