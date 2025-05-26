<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
        {
            Schema::table('zones', function (Blueprint $table) {
                $table->string('code_coursier',191)->nullable();
                $table->foreign('code_coursier',191)->references('code')->on('coursiers')->onDelete('set null'); // Lien sans suppression de la zone
            });
        }


    /**
     * Reverse the migrations.
     */
  public function down()
        {
            Schema::table('zones', function (Blueprint $table) {
                $table->dropForeign(['code_coursier']); // Supprimer la contrainte de clé étrangère
                $table->dropColumn('code_coursier'); // Supprimer la colonne elle-même
            });
        }



};
