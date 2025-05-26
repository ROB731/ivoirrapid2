<?php

// Migration pour la table statuer
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateStatuerTable extends Migration
{
    public function up()
    {
        Schema::create('statuer', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // Exemple: en attente, ramassé, déposé, annulé
            $table->timestamps();
        });

        // Insérer les valeurs par défaut dans la table `statuer`
        DB::table('statuer')->insert([
            ['name' => 'en attente'],
            ['name' => 'ramassé'],
            ['name' => 'déposé'],
            ['name' => 'annulé'],
            ['name' => 'retourné'],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('statuer');
    }
}
