<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Order\App\Models\Order;
use Modules\Product\App\Models\Product;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedDecimal('total');
            $table->unsignedDecimal('price');
            $table->unsignedInteger('quantity');
            $table->string('note')->nullable();
            $table->foreignIdFor(Order::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Product::class)->index()->constrained()->restrictOnDelete();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
