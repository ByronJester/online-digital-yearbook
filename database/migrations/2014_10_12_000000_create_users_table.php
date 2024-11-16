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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('password_text')->nullable();
            $table->string('user_type');
            $table->string('contact')->nullable();
            $table->string('school_id_no')->nullable();
            $table->string('class_batch')->nullable();
            $table->string('program')->nullable();
            $table->string('section')->nullable();
            $table->string('position')->nullable();
            $table->string('alumni_picture')->nullable();
            $table->string('profile_picture')->nullable();
            $table->text('info')->nullable();
            $table->timestamp('last_logged_in')->nullable();
            $table->timestamp('logout_at')->nullable();
            $table->boolean('is_deactivated')->default(false);

            $table->rememberToken();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
