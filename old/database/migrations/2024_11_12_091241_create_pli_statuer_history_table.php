<?php

// Migration pour la table pli_statuer_history
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePliStatuerHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('pli_statuer_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pli_id'); 
            $table->unsignedBigInteger('statuer_id'); 
            $table->timestamp('date_changement')->useCurrent();
            $table->text('raison_annulation')->nullable();
            $table->timestamps();
        
            $table->foreign('pli_id')->references('id')->on('plies')->onDelete('cascade');
            $table->foreign('statuer_id')->references('id')->on('statuer')->onDelete('cascade');
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('pli_statuer_history');
    }
}
