<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterarTabelaDevs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devs', function (Blueprint $table) {
            $table->string('github_username', 255)->nullable($value = true)->after('nome');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devs', function (Blueprint $table) {
            $table->dropColumn('github_username');
        });
    }
}
