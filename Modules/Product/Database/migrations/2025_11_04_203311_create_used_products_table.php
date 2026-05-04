<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Category\App\Models\Category;
use Modules\Client\App\Models\Client;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('used_products', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Client::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Category::class)->constrained()->restrictOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('code')->nullable()->unique();
            $table->decimal('price_egp', 10, 2)->default(0);
            $table->decimal('price_sar', 10, 2)->default(0);
            $table->decimal('discounted_price_egp', 10, 2)->default(0);
            $table->decimal('discounted_price_sar', 10, 2)->default(0);
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_sold')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('used_products');
    }
};
