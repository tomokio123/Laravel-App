<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("users")->insert([
            "name" => "yuzu",
            "email"  => "yuzu@gmail.com",
            "password"  => Hash::make("yuzuman1307"),
            "created_at"  => "2023/01/01 11:11:11",
        ]);
    }
}
