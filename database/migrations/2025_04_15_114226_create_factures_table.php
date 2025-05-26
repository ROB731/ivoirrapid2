<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->string('numero',191);
            $table->decimal('montant', 10, 2);
            $table->date('date_facture');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('factures');
    }
};
