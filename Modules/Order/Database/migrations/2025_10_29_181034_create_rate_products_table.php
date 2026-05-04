<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Order\App\Models\Rate;
use Modules\Product\App\Models\Product;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rate_products', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Rate::class)->index()->constrained()->restrictOnDelete();
            $table->foreignIdFor(Product::class)->index()->constrained()->restrictOnDelete();
            $table->unsignedTinyInteger('rate');
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rate_products');
    }
};
