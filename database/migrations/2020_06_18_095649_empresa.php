<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Empresa extends Migration
{
    public function up()
    {
        Schema::create('empresa', function (Blueprint $table) {
            $table->increments('id_empresa');
            $table->text('descricao');
            $table->text('sigla');
            $table->boolean('situacao')->default(true);
            $table->dateTime('data_cria');
            $table->dateTime('data_alt');
            $table->integer('usr_cria');
            $table->integer('usr_alt');

            $table->index(['sigla']);
            $table->index(['situacao']);

            $table->unique(['sigla']);
        }); // Schema::create('failed_jobs', function (Blueprint $table) { ...});
    }

    public function down()
    {
        Schema::dropIfExists('empresa');
    }
}
