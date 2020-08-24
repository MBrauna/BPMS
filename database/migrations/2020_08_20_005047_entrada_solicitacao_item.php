<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EntradaSolicitacaoItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entrada_solicitacao_item', function (Blueprint $table) {
            $table->increments('id_entrada_sol_item');
            $table->integer('id_entrada_solicitacao');
            $table->text('tipo');
            $table->text('questao');
            $table->text('resposta');
            $table->dateTime('data_cria');
            $table->dateTime('data_alt');
            $table->integer('usr_cria');
            $table->integer('usr_alt');

            $table->index(['id_entrada_solicitacao']);
            $table->index(['tipo']);
            $table->index(['questao']);
            $table->index(['data_cria']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
