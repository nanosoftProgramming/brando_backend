<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Cart\App\Models\Cart;
use Modules\Product\App\Models\Product;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Cart::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Product::class)->index()->constrained()->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
