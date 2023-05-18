<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

//DatabaseSeederクラス内で、callメソッドを使用して追加のシードクラスを実行できます。
//callメソッドを使用すると、データベースのシードを複数のファイルに分割して、
//単一のシーダークラスが大きくなりすぎないようにできます。
//callメソッドは、実行する必要のあるシーダークラスの配列を引数に取ります。

//要は「データベースのシードを複数のファイルに分割できる」Seeder。シーダーを実行すればOK

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            AdminSeeder::class,
            OwnerSeeder::class,
        ]);
    }
}
