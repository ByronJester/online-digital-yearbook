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
        Schema::create('success_story_likes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('success_story_id')->unsigned()->comment('Foreign key from table success_stories');
            $table->bigInteger('user_id')->unsigned()->comment('Foreign key from table users');

            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('success_story_likes');
    }
};
