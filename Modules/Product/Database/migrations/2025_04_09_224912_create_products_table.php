<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Category\App\Models\Category;
use Modules\Restaurant\App\Models\Restaurant;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('code')->nullable();
            $table->decimal('price_egp', 10, 2)->default(0);
            $table->decimal('price_sar', 10, 2)->default(0);
            $table->decimal('discounted_price_egp', 10, 2)->default(0);
            $table->decimal('discounted_price_sar', 10, 2)->default(0);
            $table->string('image')->nullable();
            $table->foreignIdFor(Category::class)->index()->constrained()->restrictOnDelete();
            $table->foreignIdFor(Restaurant::class)->index()->constrained()->cascadeOnDelete();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
