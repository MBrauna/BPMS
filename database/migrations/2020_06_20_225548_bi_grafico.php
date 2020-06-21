<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BiGrafico extends Migration
{
    public function up()
    {
        Schema::create('bi_grafico', function (Blueprint $table) {
            $table->increments('id_bi_grafico');
            $table->text('titulo');
            $table->text('subtitulo');
            $table->integer('ordem')->default(999);
            //// -------------------------------- ///
            $table->text('tipo_grafico')->default('bar');
            $table->integer('dias_periodo')->default(1);
            //// -------------------------------- ///
            $table->text('grafico1_desc')->nullable();
            $table->integer('grafico1_tipo')->nullable();
            //// -------------------------------- ///
            $table->text('grafico2_desc')->nullable();
            $table->integer('grafico2_tipo')->nullable();
            //// -------------------------------- ///
            $table->text('grafico3_desc')->nullable();
            $table->integer('grafico3_tipo')->nullable();
            //// -------------------------------- ///
            $table->text('grafico4_desc')->nullable();
            $table->integer('grafico4_tipo')->nullable();
            //// -------------------------------- ///
            $table->boolean('situacao')->default(true);
            $table->dateTime('data_cria');
            $table->dateTime('data_alt');
            $table->integer('usr_cria');
            $table->integer('usr_alt');

            $table->index(['titulo']);
            $table->index(['subtitulo']);
            $table->index(['ordem']);
            $table->index(['situacao']);
            $table->index(['tipo_grafico']);
            $table->index(['dias_periodo']);
            $table->index(['grafico1_desc']);
            $table->index(['grafico2_desc']);
            $table->index(['grafico3_desc']);
            $table->index(['grafico4_desc']);
            $table->index(['grafico1_tipo']);
            $table->index(['grafico2_tipo']);
            $table->index(['grafico3_tipo']);
            $table->index(['grafico4_tipo']);
            $table->index(['data_cria']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('bi_grafico');
    }
}
