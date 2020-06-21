<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Situacao extends Migration
{
    public function up()
    {
        Schema::create('situacao', function (Blueprint $table) {
            $table->increments('id_situacao');
            $table->integer('id_processo');
            $table->text('descricao');
            $table->integer('id_perfil');
            $table->boolean('tarefa_solicitante')->default(false);
            $table->boolean('marca_responsavel')->default(false);
            $table->boolean('alterar_data_vencimento')->default(false);
            $table->boolean('limpar_responsavel')->default(false);
            $table->boolean('conclusiva')->default(true);
            $table->boolean('situacao')->default(true);
            $table->dateTime('data_cria');
            $table->dateTime('data_alt');
            $table->integer('usr_cria');
            $table->integer('usr_alt');

            $table->index(['descricao']);
            $table->index(['id_perfil']);
            $table->index(['tarefa_solicitante']);
            $table->index(['marca_responsavel']);
            $table->index(['alterar_data_vencimento']);
            $table->index(['limpar_responsavel']);
            $table->index(['situacao']);
            $table->index(['conclusiva']);
    
            $table->unique(['id_processo','descricao']);
        }); // Schema::create('failed_jobs', function (Blueprint $table) { ...});
    }
    
    public function down()
    {
        Schema::dropIfExists('situacao');
    }
}
