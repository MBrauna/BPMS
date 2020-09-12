<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TipoProcidObject extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entrada_solicitacao',function(Blueprint $table){
            $table->integer('id_tipo_processo_destino')->nullable()->change();
            $table->integer('id_processo_origem')->nullable()->change();
            $table->integer('id_processo_destino')->nullable()->change();
            $table->integer('id_tipo_processo_origem')->nullable()->change();
            $table->integer('id_tipo_processo_destino')->nullable()->change();
            $table->integer('id_responsavel_origem')->nullable()->change();
            $table->integer('id_responsavel_destino')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
