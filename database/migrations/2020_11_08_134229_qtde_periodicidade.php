<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class QtdePeriodicidade extends Migration
{
    public function up()
    {
        Schema::table('entrada_solicitacao', function (Blueprint $table) {
            $table->integer('qtde_periodicidade')->nullable();
        });
    }

    public function down()
    {
        Schema::table('entrada_solicitacao', function (Blueprint $table) {
            $table->dropColumn(['qtde_periodicidade']);
        });
    }
}
