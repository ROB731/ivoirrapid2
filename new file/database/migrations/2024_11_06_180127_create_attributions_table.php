<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pli_id')->constrained('plies')->onDelete('cascade');
            $table->foreignId('coursier_ramassage_id')->nullable()->constrained('coursiers')->onDelete('cascade');
            $table->foreignId('coursier_depot_id')->nullable()->constrained('coursiers')->onDelete('cascade');
            // Colonnes pour les dates d'attribution
            $table->timestamp('date_attribution_ramassage')->nullable(); // Date de ramassage
            $table->timestamp('date_attribution_depot')->nullable(); // Date de dépôt
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attributions');
    }
};