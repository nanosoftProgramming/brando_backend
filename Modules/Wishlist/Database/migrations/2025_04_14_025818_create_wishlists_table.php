<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Client\App\Models\Client;
use Modules\Product\App\Models\Product;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Client::class, 'client_id')->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Product::class, 'product_id')->index()->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['client_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
