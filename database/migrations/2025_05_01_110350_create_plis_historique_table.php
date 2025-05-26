<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('plis_historique', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pli_id')->constrained('plis')->onDelete('cascade'); // Référence au pli
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade'); // Qui a modifié/supprimé ?
            $table->string('action', 100); // 'modifié' ou 'supprimé', limité à 20 caractères
            $table->text('ancienne_valeur'); // Contenu avant modification (texte long)
            $table->text('nouvelle_valeur')->nullable(); // Contenu après modification (texte long)
            $table->timestamp('date_action')->useCurrent(); // Date de l'action
        });
    }

    public function down()
    {
        Schema::dropIfExists('plis_historique');
    }
};
