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
        Schema::create('addons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price_egp', 10, 2)->default(0);
            $table->decimal('price_sar', 10, 2)->default(0);
            $table->foreignIdFor(Restaurant::class)->index()->constrained()->cascadeOnDelete();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addons');
    }
};
