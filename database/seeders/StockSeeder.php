<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
//ファサードを利用可能にする
use Illuminate\Support\Facades\DB;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('t_stocks')->insert([
            [
                'product_id' => 1,
                'type' => 1,
                'quantity' => 3, 
            ],[
                'product_id' => 1,
                'type' => 1,
                'quantity' => -2, 
            ]
        ]);
    }
}
