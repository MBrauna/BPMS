<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Perfil extends Migration
{
    public function up()
    {
        Schema::create('perfil', function (Blueprint $table) {
            $table->increments('id_perfil');
            $table->integer('id_usuario');
            $table->integer('id_empresa')->nullable();
            $table->integer('id_processo')->nullable();
            $table->integer('id_superior')->nullable();
            $table->dateTime('data_cria');
            $table->dateTime('data_alt');
            $table->integer('usr_cria');
            $table->integer('usr_alt');

            $table->index(['id_usuario']);
            $table->index(['id_empresa']);
            $table->index(['id_processo']);
            $table->index(['id_superior']);
            $table->index(['id_empresa','id_processo']);

            $table->unique(['id_usuario','id_perfil','id_empresa','id_processo']);
        }); // Schema::create('failed_jobs', function (Blueprint $table) { ...});
    }

    public function down()
    {
        Schema::dropIfExists('perfil');
    }
}
