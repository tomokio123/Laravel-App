<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId("owner_id")->constrained()//外部キー制約を追加
            ->onUpdate("cascade")
            ->onDelete("cascade");//shopは一人のオーナに対して一つbelongしている。
            //よってオーナを削除する際にこいつももろとも消さないと、エラーが出る。->php artisan migrate:refresh --seed
            $table->string("name");
            $table->text("information");//text形式
            $table->string("filename");
            $table->boolean("is_selling");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shops');
    }
}
