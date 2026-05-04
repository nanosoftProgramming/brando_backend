<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Order\App\Models\OrderDetail;
use Modules\Product\App\Models\Addon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_detail_addons', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(OrderDetail::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Addon::class)->index()->constrained()->restrictOnDelete();
            $table->integer('quantity')->default(1);
            $table->unsignedDecimal('unit_price', 10, 2);
            $table->unsignedDecimal('total_price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_detail_addons');
    }
};
