<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Cart\App\Models\CartItem;
use Modules\Product\App\Models\ProductAttributeValue;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart_item_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CartItem::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(ProductAttributeValue::class)->index()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_item_attributes');
    }
};
