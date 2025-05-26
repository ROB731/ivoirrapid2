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
        Schema::table('zones', function (Blueprint $table) {
            //
            // $table->string('libelle_zone',255);
            $table->string('libelle_zone', 255)->nullable(); // Ajout après la colonne 'nom'
        });
    }

    /**
     * Reverse the migrations.
     */
        public function down()
                {
                    Schema::table('zones', function (Blueprint $table) {
                        // $table->dropColumn('libelle_zone'); // Supprime la colonne libelle_zone
                    });
                }

};
