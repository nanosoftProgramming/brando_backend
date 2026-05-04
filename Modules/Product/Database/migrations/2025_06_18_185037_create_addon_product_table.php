<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Product\App\Models\Addon;
use Modules\Product\App\Models\Product;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('addon_product', function (Blueprint $table) {
            $table->foreignIdFor(Addon::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Product::class)->index()->constrained()->cascadeOnDelete();
            $table->primary(['addon_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addon_product');
    }
};
