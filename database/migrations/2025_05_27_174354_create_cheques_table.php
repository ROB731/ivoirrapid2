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
            Schema::create('cheques', function (Blueprint $table) {
            $table->id();
            $table->string('numero_cheque',255)->unique();
            $table->decimal('montant', 10, 2);
            $table->date('date_emission');
            $table->date('date_encaissement')->nullable();
            $table->string('beneficiaire',255);
            $table->string('banque_emettrice',255);
            $table->enum('statut', ['En attente', 'Encaisse', 'Rejeté', 'Annulé'])->default('En attente');
            $table->text('motif_rejet')->nullable();
            $table->foreignId('pli_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cheques');
    }
};
