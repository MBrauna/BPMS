<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PerfilUsuario extends Migration
{
    public function up()
    {
        Schema::create('perfil_usuario', function (Blueprint $table) {
            $table->increments('id_perfil_usuario');
            $table->integer('id_perfil');
            $table->integer('id_usuario');
            $table->integer('id_superior')->nullable();
            $table->dateTime('data_cria');
            $table->dateTime('data_alt');
            $table->integer('usr_cria');
            $table->integer('usr_alt');

            $table->index(['id_perfil']);
            $table->index(['id_usuario']);

            $table->unique(['id_perfil','id_usuario']);
        }); // Schema::create('failed_jobs', function (Blueprint $table) { ...});
    }

    public function down()
    {
        Schema::dropIfExists('perfil_usuario');
    }
}
