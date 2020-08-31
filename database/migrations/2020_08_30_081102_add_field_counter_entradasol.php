<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldCounterEntradasol extends Migration
{
    public function up()
    {
        Schema::table('entrada_solicitacao', function (Blueprint $table) {
            $table->integer('qtde_chamado')->nullable();
            $table->boolean('sla_cliente')->default(false);
            $table->boolean('sla_fornecedor')->default(false);
        });
    }

    public function down()
    {
        Schema::table('entrada_solicitacao', function (Blueprint $table) {
            $table->dropColumn(['qtde_chamado']);
            $table->dropColumn(['sla_cliente']);
            $table->dropColumn(['sla_fornecedor']);
        });
    }
}
