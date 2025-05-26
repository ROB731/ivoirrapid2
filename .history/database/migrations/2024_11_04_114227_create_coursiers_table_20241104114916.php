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
        Schema::create('coursiers', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenoms');
            $table->string('telephone');
            $table->string('email')->nullable();
            $table->string('code');
            $table->string('numero_de_permis');
            $table->date('date_de_validite_du_permis');
            $table->string('categorie_du_permis');
            $table->string('numero_de_cni');
            $table->date('date_de_validite_de_la_cni');
            $table->string('numero_de_la_cmu');
            $table->date('date_de_validite_de_la_cmu');
            $table->date('date_de_naissance');
            $table->string('groupe_sanguin');
            $table->date('date_de_debut_du_contrat');
            $table->date('date_de_fin_du_contrat');
            $table->date('');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coursiers');
    }
};
