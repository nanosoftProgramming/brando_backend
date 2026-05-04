<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Client\App\Models\Client;
use Modules\Country\App\Models\City;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string(column: 'title');
            $table->foreignIdFor(Client::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(City::class)->index()->constrained()->cascadeOnDelete();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 10, 8)->nullable();
            $table->string('block')->nullable();
            $table->string('street')->nullable();
            $table->string('house_number')->nullable();
            $table->string('notes')->nullable();
            $table->boolean('default')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
