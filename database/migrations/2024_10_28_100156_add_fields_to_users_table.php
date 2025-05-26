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
    //     Schema::table('users', function (Blueprint $table) {
    //         $table->string('abreviation');
    //         $table->string('Telephone')->unique()->length(10);
    //         $table->string('Cellulaire')->unique()->length(10);
    //         $table->string('Compte_contribuable')->unique()->length(9);
    //         $table->string('RCCM')->nullable();
    //         $table->string('Direction_1_Nom_et_Prenoms')->unique();
    //         $table->string('Direction_1_Contact')->unique();
    //         $table->string('Direction_2_Nom_et_Prenoms')->nullable();
    //         $table->string('Direction_2_Contact')->nullable();
    //         $table->string('Direction_3_Nom_et_Prenoms')->nullable();
    //         $table->string('Direction_3_Contact')->nullable();
    //         $table->string('Adresse')->nullable();
    //         $table->string('Commune');
    //         $table->string('Quartier');
    //         $table->string('Rue');
    //         $table->string('Zone');
    //         $table->longText('Autre')->nullable();
    //     });
    // }



    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        if (!Schema::hasColumn('users', 'abreviation')) {
            $table->string('abreviation', 191);
        }
        if (!Schema::hasColumn('users', 'Telephone')) {
            $table->string('Telephone', 10)->unique();
        }
        if (!Schema::hasColumn('users', 'Cellulaire')) {
            $table->string('Cellulaire', 10)->unique();
        }
        if (!Schema::hasColumn('users', 'Compte_contribuable')) {
            $table->string('Compte_contribuable', 9)->unique();
        }
        if (!Schema::hasColumn('users', 'RCCM')) {
            $table->string('RCCM', 255)->nullable();
        }
        if (!Schema::hasColumn('users', 'Direction_1_Nom_et_Prenoms')) {
            $table->string('Direction_1_Nom_et_Prenoms', 191)->unique();
        }
        if (!Schema::hasColumn('users', 'Direction_1_Contact')) {
            $table->string('Direction_1_Contact', 191)->unique();
        }
        if (!Schema::hasColumn('users', 'Direction_2_Nom_et_Prenoms')) {
            $table->string('Direction_2_Nom_et_Prenoms', 191)->nullable();
        }
        if (!Schema::hasColumn('users', 'Direction_2_Contact')) {
            $table->string('Direction_2_Contact', 191)->nullable();
        }
        if (!Schema::hasColumn('users', 'Direction_3_Nom_et_Prenoms')) {
            $table->string('Direction_3_Nom_et_Prenoms', 191)->nullable();
        }
        if (!Schema::hasColumn('users', 'Direction_3_Contact')) {
            $table->string('Direction_3_Contact', 191)->nullable();
        }
        if (!Schema::hasColumn('users', 'Adresse')) {
            $table->string('Adresse', 191)->nullable();
        }
        if (!Schema::hasColumn('users', 'Commune')) {
            $table->string('Commune', 191);
        }
        if (!Schema::hasColumn('users', 'Quartier')) {
            $table->string('Quartier', 191);
        }
        if (!Schema::hasColumn('users', 'Rue')) {
            $table->string('Rue', 191);
        }
        if (!Schema::hasColumn('users', 'Zone')) {
            $table->string('Zone', 191);
        }
        if (!Schema::hasColumn('users', 'Autre')) {
            $table->longText('Autre')->nullable();
        }
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
           $table->dropColumn('abreviation');
           $table->dropColumn('Telephone');
           $table->dropColumn('Cellulaire');
           $table->dropColumn('Compte_contribuable');
           $table->dropColumn('RCCM');
           $table->dropColumn('Direction_1_Nom_et_Prenoms');
           $table->dropColumn('Direction_1_Contact');
           $table->dropColumn('Direction_2_Nom_et_Prenoms');
           $table->dropColumn('Direction_2_Contact');
           $table->dropColumn('Direction_3_Nom_et_Prenoms');
           $table->dropColumn('Direction_3_Contact');
           $table->dropColumn('Adresse');
           $table->dropColumn('Commune');
           $table->dropColumn('Quartier');
           $table->dropColumn('Rue');
           $table->dropColumn('Zone');
           $table->dropColumn('Autre');
        });
    }
};
