<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsuarioSuperior extends Migration
{
    public function up()
    {
        Schema::create('arvore_usuario', function (Blueprint $table) {
            $table->increments('id_arvore_usuario');
            $table->integer('id_usuario_superior');
            $table->integer('id_usuario');
            $table->integer('id_empresa');
            $table->integer('id_processo');
            $table->dateTime('data_cria');
            $table->dateTime('data_alt');
            $table->integer('usr_cria');
            $table->integer('usr_alt');

            $table->index(['id_usuario_superior']);
            $table->index(['id_usuario']);
            $table->index(['id_empresa']);
            $table->index(['id_processo']);

            $table->unique(['id_usuario_superior','id_usuario','id_empresa','id_processo']);
        }); // Schema::create('failed_jobs', function (Blueprint $table) { ...});
    }

    public function down()
    {
        Schema::dropIfExists('arvore_usuario');
    }
}
