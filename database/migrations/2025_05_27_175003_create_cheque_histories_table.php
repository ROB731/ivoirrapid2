<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('cheque_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cheque_id')->constrained()->onDelete('cascade');
            $table->enum('ancien_statut', ['En attente', 'Encaisse', 'Rejeté', 'Annulé']);
            $table->enum('nouveau_statut', ['En attente', 'Encaisse', 'Rejeté', 'Annulé']);
            $table->timestamp('date_changement')->useCurrent();
            $table->text('motif_changement')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cheque_histories');
    }
};
