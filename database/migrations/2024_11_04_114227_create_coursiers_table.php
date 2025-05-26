<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // public function up(): void
    // {
    //     Schema::create('coursiers', function (Blueprint $table) {
    //         $table->id();
    //         $table->string('nom',191); // Suppression de unique()
    //         $table->string('prenoms',191); // Suppression de unique()
    //         $table->string('telephone',10); // Important de garder unique()
    //         $table->string('email',191); // Utile si chaque email est unique
    //         $table->string('code',255); // Gardé unique car souvent utilisé comme identifiant unique
    //         $table->string('numero_de_permis',191); // Unique, car lié à une identité légale
    //         $table->date('date_de_validite_du_permis');
    //         $table->string('categorie_du_permis',191);
    //         $table->string('numero_de_cni',191); // Gardé unique, identité légale
    //         $table->date('date_de_validite_de_la_cni');
    //         $table->string('numero_de_la_cmu',191); // Unique si chaque coursier a un CMU unique
    //         $table->date('date_de_validite_de_la_cmu');
    //         $table->date('date_de_naissance');
    //         $table->string('groupe_sanguin',191);
    //         $table->date('date_de_debut_du_contrat');
    //         $table->date('date_de_fin_du_contrat');
    //         $table->string('adresse',191);
    //         $table->string('contact_urgence',10); // Suppression de unique()
    //         $table->string('affiliation_urgence',191);
    //         $table->text('zones',255)->unique();
    //         $table->timestamps();
    //     });
    // }


    public function up()
{
    if (!Schema::hasTable('coursiers')) {
        Schema::create('coursiers', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 191);
            $table->string('prenoms', 191);
            $table->string('telephone', 10);
            $table->string('email', 191);
            $table->string('code', 255);
            $table->string('numero_de_permis', 191);
            $table->date('date_de_validite_du_permis');
            $table->string('categorie_du_permis', 191);
            $table->string('numero_de_cni', 191);
            $table->date('date_de_validite_de_la_cni');
            $table->string('numero_de_la_cmu', 191);
            $table->date('date_de_validite_de_la_cmu');
            $table->date('date_de_naissance');
            $table->string('groupe_sanguin', 191);
            $table->date('date_de_debut_du_contrat');
            $table->date('date_de_fin_du_contrat');
            $table->string('adresse', 191);
            $table->string('contact_urgence', 10);
            $table->string('affiliation_urgence', 191);
            $table->text('zones');
            $table->timestamps();
        });
    }
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coursiers');
    }
};
