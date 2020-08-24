<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTipo extends Migration
{
    public function up()
    {
        Schema::table('tipo_processo', function (Blueprint $table) {
            $table->boolean('automatico')->default(false);
        });
    }

    public function down()
    {
        Schema::table('tipo_processo', function (Blueprint $table) {
            $table->dropColumn(['automatico']);
        });
    }
}
