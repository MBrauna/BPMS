<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Tarefa extends Migration
{
    public function up()
    {
        Schema::create('tarefa', function (Blueprint $table) {
            $table->increments('id_tarefa');
            $table->integer('id_chamado');
            $table->text('conteudo');
            $table->integer('id_situacao_anterior');
            $table->integer('id_situacao_atribuida');
            $table->integer('id_usuario_atribuido')->nullable();
            $table->dateTime('data_venc_anterior')->nullable();
            $table->dateTime('data_venc_atribuida')->nullable();
            $table->dateTime('data_cria');
            $table->dateTime('data_alt');
            $table->integer('usr_cria');
            $table->integer('usr_alt');

            $table->index(['id_chamado']);
            $table->index(['id_situacao_anterior']);
            $table->index(['id_situacao_atribuida']);
            $table->index(['data_venc_anterior']);
            $table->index(['data_venc_atribuida']);
            $table->index(['data_cria']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tarefa');
    }
}
