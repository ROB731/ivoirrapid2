<?php

// use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;

// return new class extends Migration
// {
//     /**
//      * Run the migrations.
//      */
//     public function up(): void
//     {
//         Schema::create('plies', function (Blueprint $table) {
//             $table->id();
//             $table->foreignId('destinataire_id')->constrained()->onDelete('cascade'); // Lien vers le destinataire
//             $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Lien vers l'expéditeur (user)

//             // Informations sur le destinataire
//             $table->string('destinataire_name' ,191); // Nom du destinataire
//             $table->string('destinataire_adresse' , 10); // Adresse du destinataire
//             $table->string('destinataire_telephone', 10); // Téléphone du destinataire
//             $table->string('destinataire_email', 191)->nullable(); // Email du destinataire
//             $table->string('destinataire_zone' , 191)->nullable(); // Zone du destinataire
//             $table->string('destinataire_contact' , 191)->nullable(); // Contact alternatif du destinataire
//             $table->string('destinataire_autre' , 191)->nullable(); // Autres informations sur le destinataire

//             // Informations sur le user (expéditeur)
//             $table->string('user_name' , 191); // Nom du user
//             $table->string('user_Adresse' , 10); // Adresse du user
//             $table->string('user_Telephone' ,10); // Téléphone du user
//             $table->string('user_email' , 191)->nullable(); // Email du user
//             $table->string('user_Zone' , 191)->nullable(); // Zone du user
//             $table->string('user_Cellulaire' , 191)->nullable(); // Contact alternatif du user
//             $table->string('user_Autre' , 191)->nullable(); // Autres informations sur le user

//             // Informations sur le colis
//             $table->string('type' , 191);
//             $table->string('nombre_de_pieces', 10);
//             $table->string('reference', 191);

//             $table->timestamps();
//         });
//     }

//     /**
//      * Reverse the migrations.
//      */
//     public function down(): void
//     {
//         Schema::dropIfExists('plies');
//     }
// };



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
            $table->string('destinataire_name', 191); // Nom du destinataire
            $table->string('destinataire_adresse', 255); // Adresse du destinataire
            $table->string('destinataire_telephone', 15); // Téléphone du destinataire
            $table->string('destinataire_email', 191)->nullable(); // Email du destinataire
            $table->string('destinataire_zone', 191)->nullable(); // Zone du destinataire
            $table->string('destinataire_contact', 191)->nullable(); // Contact alternatif du destinataire
            $table->string('destinataire_autre', 191)->nullable(); // Autres informations sur le destinataire

            // Informations sur l'expéditeur (user)
            $table->string('user_name', 191); // Nom de l'expéditeur
            $table->string('user_adresse', 255); // Adresse de l'expéditeur
            $table->string('user_telephone', 15); // Téléphone de l'expéditeur
            $table->string('user_email', 191)->nullable(); // Email de l'expéditeur
            $table->string('user_zone', 191)->nullable(); // Zone de l'expéditeur
            $table->string('user_cellulaire', 191)->nullable(); // Contact alternatif de l'expéditeur
            $table->string('user_autre', 191)->nullable(); // Autres infos de l'expéditeur

            // Informations sur le colis
            $table->string('type', 191);
            $table->integer('nombre_de_pieces'); // Utilisation de `integer` au lieu de `string`
            $table->string('reference', 191)->unique(); // Ajout de `unique()` pour éviter les doublons

            // Ajout des timestamps et SoftDeletes
            $table->timestamps();
            $table->softDeletes(); // Activation du `deleted_at`
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
