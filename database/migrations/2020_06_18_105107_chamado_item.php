<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChamadoItem extends Migration
{
    public function up()
    {
        Schema::create('chamado_item', function (Blueprint $table) {
            $table->increments('id_chamado_item');
            $table->integer('id_chamado');
            $table->text('tipo');
            $table->text('questao');
            $table->text('resposta');
            $table->dateTime('data_cria');
            $table->dateTime('data_alt');
            $table->integer('usr_cria');
            $table->integer('usr_alt');

            $table->index(['id_chamado']);
            $table->index(['tipo']);
            $table->index(['questao']);
            $table->index(['data_cria']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('chamado_item');
    }
}
