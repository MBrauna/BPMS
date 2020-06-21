<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PerguntaTipo extends Migration
{
    public function up()
    {
        Schema::create('pergunta_tipo', function (Blueprint $table) {
            $table->increments('id_pergunta_tipo');
            $table->integer('id_tipo_processo');
            $table->text('descricao');
            $table->text('tipo');
            $table->integer('ordem')->default(999);
            $table->boolean('multiline')->default(false);
            $table->boolean('alt_data_vencimento')->default(false);
            $table->boolean('situacao')->default(true);
            $table->dateTime('data_cria');
            $table->dateTime('data_alt');
            $table->integer('usr_cria');
            $table->integer('usr_alt');

            $table->index(['situacao']);
            $table->index(['id_tipo_processo']);
            $table->index(['tipo']);
            $table->index(['ordem']);
            $table->index(['multiline']);
            $table->index(['alt_data_vencimento']);
    
            $table->unique(['id_tipo_processo','descricao']);
            $table->unique(['id_tipo_processo','ordem']);
        }); // Schema::create('failed_jobs', function (Blueprint $table) { ...});
    }
    
    public function down()
    {
        Schema::dropIfExists('pergunta_tipo');
    }
}
