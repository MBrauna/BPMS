<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BiSintetico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bi_sintetico',function(Blueprint $table){
            $table->increments('id_bi_sintetico');
            $table->dateTime('data');
            $table->integer('id_empresa');
            $table->integer('id_processo')->nullable();
            $table->integer('id_bi_tipo')->nullable();
            $table->integer('quantidade')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bi_sintetico');
    }
}
