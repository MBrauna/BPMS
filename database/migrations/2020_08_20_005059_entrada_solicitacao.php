<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EntradaSolicitacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entrada_solicitacao', function (Blueprint $table) {
            $table->increments('id_entrada_solicitacao');
            $table->integer('tipo');
            $table->integer('id_processo_origem')->nullable();
            $table->integer('id_processo_destino')->nullable();
            $table->integer('id_tipo_processo_origem')->nullable();
            $table->integer('id_tipo_processo_destino')->nullable();
            $table->integer('id_responsavel_origem')->nullable();
            $table->integer('id_responsavel_destino')->nullable();
            $table->integer('periodicidade');
            $table->dateTime('data_criacao');
            $table->dateTime('data_primeiro_agendamento');
            $table->dateTime('data_proximo_agendamento')->nullable();
            $table->integer('id_solicitante');
            $table->text('url');
            $table->text('titulo');
            $table->integer('tipo_objeto')->nullable();
            $table->integer('meio')->nullable();
            $table->boolean('situacao')->default(true);
            $table->dateTime('data_cria');
            $table->dateTime('data_alt');
            $table->integer('usr_cria');
            $table->integer('usr_alt');

            $table->index(['tipo']);
            $table->index(['id_processo_origem']);
            $table->index(['id_processo_destino']);
            $table->index(['id_tipo_processo_origem']);
            $table->index(['id_tipo_processo_destino']);
            $table->index(['id_responsavel_origem']);
            $table->index(['id_responsavel_destino']);
            $table->index(['periodicidade']);
            $table->index(['data_criacao']);
            $table->index(['data_primeiro_agendamento']);
            $table->index(['data_proximo_agendamento']);
            $table->index(['id_solicitante']);
            $table->index(['url']);
            $table->index(['tipo_objeto']);
            $table->index(['meio']);
            $table->index(['situacao']);
            $table->index(['data_cria']);
            $table->index(['usr_cria']);
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
