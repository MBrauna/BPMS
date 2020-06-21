<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BiTipo extends Migration
{
    public function up()
    {
        Schema::create('bi_tipo', function (Blueprint $table) {
            $table->increments('id_bi_tipo');
            $table->text('descricao');
            $table->boolean('situacao')->default(true);
            $table->dateTime('data_cria');
            $table->dateTime('data_alt');
            $table->integer('usr_cria');
            $table->integer('usr_alt');

            $table->index(['situacao']);
            $table->index(['data_cria']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('bi_tipo');
    }
}
