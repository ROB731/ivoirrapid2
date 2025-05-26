<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('plies', function (Blueprint $table) {
        $table->date('date_attribution_ramassage')->nullable();
        $table->date('date_attribution_depot')->nullable();
    });
}

public function down()
{
    Schema::table('plies', function (Blueprint $table) {
        $table->dropColumn('date_attribution_ramassage');
        $table->dropColumn('date_attribution_depot');
    });
}

};
