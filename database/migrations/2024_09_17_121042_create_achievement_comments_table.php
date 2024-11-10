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
        Schema::create('achievement_comments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('achievement_id')->unsigned()->comment('Foreign key from table achievements');
            $table->bigInteger('user_id')->unsigned()->comment('Foreign key from table users');
            $table->text('comment');

            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievement_comments');
    }
};
