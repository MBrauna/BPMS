<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PerfilAcesso extends Migration
{
    public function up()
    {
        Schema::create('perfil_acesso', function (Blueprint $table) {
            $table->increments('id_perfil_acesso');
            $table->integer('id_perfil');
            $table->integer('id_empresa')->nullable();
            $table->integer('id_processo')->nullable();
            $table->dateTime('data_cria');
            $table->dateTime('data_alt');
            $table->integer('usr_cria');
            $table->integer('usr_alt');

            $table->index(['id_perfil']);
            $table->index(['id_empresa']);
            $table->index(['id_processo']);

            $table->unique(['id_perfil','id_empresa','id_processo']);
        }); // Schema::create('failed_jobs', function (Blueprint $table) { ...});
    }

    public function down()
    {
        Schema::dropIfExists('perfil_acesso');
    }
}
