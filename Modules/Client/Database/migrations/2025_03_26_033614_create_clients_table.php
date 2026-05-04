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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('password');
            $table->date(column: 'date_of_birth')->nullable();
            $table->string('image')->nullable();
            $table->string('verify_code');
            $table->string('fcm_token')->nullable();
            $table->boolean('is_active')->default(0);
            $table->boolean('allow_notification')->default(1);
            $table->string('auth_id')->nullable();
            $table->enum('auth_type', ['facebook', 'google'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
