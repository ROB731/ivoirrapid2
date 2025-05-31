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
            Schema::table('users', function (Blueprint $table) {
                $table->text('facebook_name')->nullable();
                $table->text('facebook_link')->nullable();
                $table->text('instagram_name')->nullable();
                $table->text('instagram_link')->nullable();
                $table->text('website_name')->nullable();
                $table->text('website_link')->nullable();
                $table->text('folder_name')->nullable();
                $table->text('folder_link')->nullable();
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
