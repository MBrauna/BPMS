<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BiChamado extends Migration
{
    public function up()
    {
        Schema::create('bi_chamado', function (Blueprint $table) {
            $table->increments('id_bi_chamado');
            $table->integer('id_bi_tipo');
            $table->integer('id_chamado');
            $table->integer('id_empresa');
            $table->integer('id_processo');
            $table->dateTime('data');
            $table->boolean('situacao')->default(true);
            $table->dateTime('data_cria');
            $table->dateTime('data_alt');
            $table->integer('usr_cria');
            $table->integer('usr_alt');

            $table->index(['id_chamado']);
            $table->index(['id_bi_tipo']);
            $table->index(['data']);
            $table->index(['situacao']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('bi_chamado');
    }
}
