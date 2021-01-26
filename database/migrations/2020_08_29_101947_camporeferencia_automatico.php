<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CamporeferenciaAutomatico extends Migration
{
    public function up()
    {
        Schema::table('entrada_solicitacao', function (Blueprint $table) {
            $table->integer('id_processo_referencia')->nullable();
        });
    }

    public function down()
    {
        Schema::table('entrada_solicitacao', function (Blueprint $table) {
            $table->dropColumn(['id_processo_referencia']);
        });
    }
}
