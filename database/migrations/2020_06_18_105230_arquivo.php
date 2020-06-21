<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Arquivo extends Migration
{
    public function up()
    {
        Schema::create('arquivo', function (Blueprint $table) {
            $table->increments('id_arquivo');
            $table->integer('id_chamado')->nullable();
            $table->integer('id_tarefa')->nullable();
            $table->integer('id_para_usuario')->nullable();
            $table->text('nome_arquivo');
            $table->text('extensao')->nullable();
            $table->text('mime')->nullable();
            $table->double('tamanho',12,2)->nullable();
            $table->dateTime('data_cria');
            $table->dateTime('data_alt');
            $table->integer('usr_cria');
            $table->integer('usr_alt');

            $table->index(['id_chamado']);
            $table->index(['id_tarefa']);
            $table->index(['id_para_usuario']);
            $table->index(['nome_arquivo']);
            $table->index(['extensao']);
            $table->index(['mime']);
            $table->index(['tamanho']);
            $table->index(['data_cria']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('arquivo');
    }
}
