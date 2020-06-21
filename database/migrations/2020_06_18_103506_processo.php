<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Processo extends Migration
{
    public function up()
    {
        Schema::create('processo', function (Blueprint $table) {
            $table->increments('id_processo');
            $table->integer('id_empresa');
            $table->text('descricao');
            $table->text('sigla')->nullable();
            $table->text('icone')->nullable();
            $table->integer('id_situacao');
            $table->integer('id_usr_responsavel')->nullable();
            $table->boolean('situacao')->default(true);
            $table->dateTime('data_cria');
            $table->dateTime('data_alt');
            $table->integer('usr_cria');
            $table->integer('usr_alt');

            $table->index(['id_empresa']);
            $table->index(['descricao']);
            $table->index(['icone']);
            $table->index(['id_situacao']);
            $table->index(['id_usr_responsavel']);
            $table->index(['situacao']);

            $table->unique(['sigla']);
        }); // Schema::create('failed_jobs', function (Blueprint $table) { ...});
    }

    public function down()
    {
        Schema::dropIfExists('processo');
    }
}
