<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('trafic_stats', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('total_plis')->default(0);
            $table->integer('total_factures')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trafic_stats');
    }
};
