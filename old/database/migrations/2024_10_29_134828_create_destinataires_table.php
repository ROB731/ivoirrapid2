<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDestinatairesTable extends Migration
{
    public function up()
    {
        // Schema::create('destinataires', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name',191); // Nom du destinataire
        //     $table->string('email',191)->nullable(); // Email du destinataire
        //     $table->string('telephone',10)->unique(); // Numéro de téléphone
        //     $table->string('contact', 10)->unique(); // Contact
        //     $table->string('adresse',191)->nullable(); // Adresse
        //     $table->string('zone')->nullable(); // Zone géographique
        //     $table->string('autre')->nullable(); // Autres informations
        //     $table->unsignedBigInteger('user_id'); // ID de l'utilisateur associé
        //     $table->timestamps();

        //     // Clé étrangère pour user_id
        //     $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        //     // Index unique composite pour éviter les doublons par utilisateur
        //     $table->unique(['name', 'user_id'], 'unique_name_per_user');
        // });


        //--------------------------

        if (!Schema::hasTable('destinataires')) {
            Schema::create('destinataires', function (Blueprint $table) {
                $table->id();
                $table->string('name', 191);
                $table->string('email', 191)->nullable();
                $table->string('telephone', 10);
                $table->string('contact', 10);
                $table->string('adresse', 191)->nullable();
                $table->string('zone', 255)->nullable();
                $table->string('autre', 255)->nullable();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

                // Index unique composite pour éviter les doublons par utilisateur
                $table->unique(['name', 'user_id'], 'unique_name_per_user');
            });
        }


        // Supprimez cette table pivot si elle n'est pas nécessaire
        Schema::create('user_destinataire', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('destinataire_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_destinataire');
        Schema::dropIfExists('destinataires');
    }
}
