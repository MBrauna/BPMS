<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TipoProcesso extends Migration
{
    public function up()
    {
        Schema::create('tipo_processo', function (Blueprint $table) {
            $table->increments('id_tipo_processo');
            $table->integer('id_processo');
            $table->text('descricao');
            $table->integer('id_processo_redireciona')->nullable();
            $table->text('questao');
            $table->integer('ordem')->default(999);
            $table->integer('sla')->default(72);
            $table->boolean('permite_alterar_sla')->default(true);
            $table->boolean('situacao')->default(true);
            $table->dateTime('data_cria');
            $table->dateTime('data_alt');
            $table->integer('usr_cria');
            $table->integer('usr_alt');

            $table->index(['descricao']);
            $table->index(['id_processo']);
            $table->index(['id_processo_redireciona']);
            $table->index(['ordem']);
            $table->index(['sla']);
            $table->index(['permite_alterar_sla']);
            $table->index(['situacao']);
    
            $table->unique(['id_processo','descricao']);
        }); // Schema::create('failed_jobs', function (Blueprint $table) { ...});
    }
    
    public function down()
    {
        Schema::dropIfExists('tipo_processo');
    }
}
