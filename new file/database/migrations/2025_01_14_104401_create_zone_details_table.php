<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {


        // Schema::create('zone_details', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('zone_id');
        //     $table->integer('NumeroZone'); // Numéro unique pour chaque zone dans la plage
        //     $table->string('NomZone');
        //     $table->string('CoursierName');
        //     $table->string('CoursierCode');
        //     $table->string('CoursierPhone');
        //     $table->timestamps();

        //     $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');
        // });



        if (!Schema::hasTable('zone_details')) {
            Schema::create('zone_details', function (Blueprint $table) {
                $table->id();
                $table->foreignId('zone_id')->constrained()->onDelete('cascade');
                $table->integer('NumeroZone');
                $table->string('NomZone');
                $table->string('CoursierName');
                $table->string('CoursierCode');
                $table->string('CoursierPhone');
                $table->timestamps();

                $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');
            });
        }



    }




    public function down(): void
    {
        Schema::dropIfExists('zone_details');
    }
};



//New elemennt




