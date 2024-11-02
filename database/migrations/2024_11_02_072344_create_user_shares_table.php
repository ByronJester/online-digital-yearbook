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
        Schema::create('user_shares', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->comment('Foreign key from table users')->nullable();
            $table->bigInteger('achivement_id')->unsigned()->comment('Foreign key from table achivements')->nullable();
            $table->bigInteger('album_id')->unsigned()->comment('Foreign key from table album')->nullable();
            $table->string('type');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_shares');
    }
};
