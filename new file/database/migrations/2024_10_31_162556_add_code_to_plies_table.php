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
    // Schema::table('plies', function (Blueprint $table) {
    //     $table->string('code',255)->unique()->nullable();
    // });

    Schema::table('plies', function (Blueprint $table) {
        if (!Schema::hasColumn('plies', 'code')) {
            $table->string('code', 255)->nullable();
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plies', function (Blueprint $table) {
            //
        });
    }
};
