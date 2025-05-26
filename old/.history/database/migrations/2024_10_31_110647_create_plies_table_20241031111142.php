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
        Schema::create('plies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destinataire_id')->constrained()->onDelete('cascade'); // Lien vers le destinataire
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Lien vers l'expéditeur (user)

            // Informations sur le destinataire
            $table->string('destinataire_name'); // Nom du destinataire
            $table->string('destinataire_adresse'); // Adresse du destinataire
            $table->string('destinataire_telephone'); // Téléphone du destinataire
            $table->string('destinataire_email')->nullable(); // Email du destinataire
            $table->string('destinataire_zone')->nullable(); // Zone du destinataire
            $table->string('destinataire_contact')->nullable(); // Contact alternatif du destinataire
            $table->string('destinataire_autre')->nullable(); // Autres informations sur le destinataire

            // Informations sur le user (expéditeur)
            $table->string('user_name'); // Nom du user
            $table->string('user_Adresse'); // Adresse du user
            $table->string('user_Telephone'); // Téléphone du user
            $table->string('user_email')->nullable(); // Email du user
            $table->string('user_Zone')->nullable(); // Zone du user
            $table->string('user_Cellulaire')->nullable(); // Contact alternatif du user
            $table->string('user_Autre')->nullable(); // Autres informations sur le user

            // Informations sur le colis
            $table->string('type');
            $table->string('nombre_de_pieces');
            // Description du colis
            $table->timestamps(); // Timestamps pour created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plies');
    }
};
