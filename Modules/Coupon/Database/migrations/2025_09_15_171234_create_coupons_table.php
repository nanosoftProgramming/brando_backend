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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->integer('num_of_uses');
            $table->integer('counter')->default(0);
            $table->tinyInteger('type')->comment('1 => fixed, 2 => percentage');
            $table->integer('value');
            $table->integer('limit')->default(0);
            $table->integer('client_uses')->default(1);
            $table->string('discount_on')->nullable()->comment('1 => subtotal, 2 => delivery fee, 3 => both ');
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->time('time_from')->nullable();
            $table->time('time_to')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
