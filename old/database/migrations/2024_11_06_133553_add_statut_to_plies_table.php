<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatutToPliesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('plies', function (Blueprint $table) {
            $table->enum('statut', ['non_attribue', 'attribue'])->default('non_attribue');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('plies', function (Blueprint $table) {
            $table->dropColumn('statut');
        });
    }
}
