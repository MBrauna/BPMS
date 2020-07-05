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
            $table->text('descricao');
            $table->boolean('situacao')->default(true);
            $table->dateTime('data_cria');
            $table->dateTime('data_alt');
            $table->integer('usr_cria');
            $table->integer('usr_alt');

            $table->index(['descricao']);
            $table->index(['situacao']);
        }); // Schema::create('failed_jobs', function (Blueprint $table) { ...});
    }

    public function down()
    {
        Schema::dropIfExists('perfil');
    }
}
