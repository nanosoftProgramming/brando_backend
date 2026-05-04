<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->enum('item_type', ['product', 'used_product'])->default('product')->after('product_id');
            $table->foreignId('used_product_id')->nullable()->after('item_type')->constrained('used_products')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn(['item_type', 'used_product_id']);
        });
    }
};
