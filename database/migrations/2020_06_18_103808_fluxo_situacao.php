<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FluxoSituacao extends Migration
{
    public function up()
    {
        Schema::create('fluxo_situacao', function (Blueprint $table) {
            $table->increments('id_fluxo_situacao');
            $table->integer('id_tipo_processo');
            $table->integer('id_situacao_atual');
            $table->integer('id_situacao_posterior');
            $table->boolean('situacao')->default(true);
            $table->dateTime('data_cria');
            $table->dateTime('data_alt');
            $table->integer('usr_cria');
            $table->integer('usr_alt');

            $table->index(['id_tipo_processo']);
            $table->index(['id_situacao_atual']);
            $table->index(['id_situacao_posterior']);
            $table->index(['situacao']);
    
            $table->unique(['id_tipo_processo','id_situacao_atual','id_situacao_posterior']);
        }); // Schema::create('failed_jobs', function (Blueprint $table) { ...});
    }
    
    public function down()
    {
        Schema::dropIfExists('fluxo_situacao');
    }
}
