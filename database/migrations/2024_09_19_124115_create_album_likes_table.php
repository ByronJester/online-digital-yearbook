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
        Schema::create('album_likes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('album_id')->unsigned()->comment('Foreign key from table albums');
            $table->bigInteger('user_id')->unsigned()->comment('Foreign key from table users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('album_likes');
    }
};
