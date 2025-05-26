<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        //Here
        if (!Schema::hasTable('zones')) {
            Schema::create('zones', function (Blueprint $table) {
                $table->id();
                $table->string('Commune');
                $table->string('PlageZone');
                $table->timestamps();
            });
        }



    }

    public function down(): void
    {
        Schema::dropIfExists('zones');
    }
};
