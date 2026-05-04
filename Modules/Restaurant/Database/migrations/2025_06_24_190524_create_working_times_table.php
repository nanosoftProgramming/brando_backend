<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Restaurant\App\Models\Restaurant;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('working_times', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Restaurant::class)->constrained()->cascadeOnDelete();
            $table->enum('day', ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday']);
            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();
            $table->boolean('is_closed')->default(0);
            $table->unique(['restaurant_id', 'day']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('working_times');
    }
};
