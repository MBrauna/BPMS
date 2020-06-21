<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Chamado extends Migration
{
    public function up()
    {
        Schema::create('chamado', function (Blueprint $table) {
            $table->increments('id_chamado');
            $table->integer('id_situacao');
            $table->integer('id_empresa');
            $table->integer('id_processo');
            $table->integer('id_tipo_processo');
            $table->dateTime('data_criacao');
            $table->dateTime('data_vencimento');
            $table->dateTime('data_conclusao')->nullable();
            $table->integer('id_solicitante');
            $table->integer('id_responsavel')->nullable();
            $table->text('url');
            $table->text('titulo');
            $table->boolean('situacao')->default(true);
            $table->dateTime('data_cria');
            $table->dateTime('data_alt');
            $table->integer('usr_cria');
            $table->integer('usr_alt');

            $table->index(['id_situacao']);
            $table->index(['id_empresa']);
            $table->index(['id_processo']);
            $table->index(['id_tipo_processo']);
            $table->index(['data_criacao']);
            $table->index(['data_vencimento']);
            $table->index(['data_conclusao']);
            $table->index(['id_solicitante']);
            $table->index(['id_responsavel']);
            $table->index(['url']);
            $table->index(['situacao']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('chamado');
    }
}
