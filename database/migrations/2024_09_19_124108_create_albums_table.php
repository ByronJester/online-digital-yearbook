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
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->comment('Foreign key from table users');
            $table->json('share_user_names')->nullable();
            $table->text('content');
            $table->date('archive_at');
            $table->text('description')->nullable();
            $table->json('image')->nullable();
            $table->json('video')->nullable();

            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
};
