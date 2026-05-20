<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Client\App\Models\Address;
use Modules\Client\App\Models\Client;
use Modules\Coupon\App\Models\Coupon;
use Modules\Order\App\Models\OrderStatus;
use Modules\Restaurant\App\Models\Restaurant;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique();
            $table->unsignedDecimal('subtotal');
            $table->unsignedDecimal('discount')->default(0);
            $table->unsignedTinyInteger('discount_type')->index()->nullable();
            $table->unsignedDecimal('tax')->default(0);
            $table->unsignedDecimal('delivery_fee')->default(0);
            $table->unsignedDecimal('total');
            $table->string('currency', 3)->default('egp');
            $table->unsignedInteger('quantity')->default(0);
            $table->string('note')->nullable();
            $table->foreignIdFor(Client::class)->index()->constrained()->restrictOnDelete();
            $table->foreignIdFor(Address::class)->index()->constrained()->restrictOnDelete();
            $table->foreignIdFor(Restaurant::class)->index()->constrained()->cascadeOnDelete();
            // $table->foreignIdFor(Coupon::class)->nullable()->index()->constrained()->restrictOnDelete();
    
    $table->foreignId('coupon_id')
    ->nullable()
    ->constrained('coupons')
    ->nullOnDelete();
            $table->enum('payment_method', ['cash', 'visa', 'online']);
            $table->foreignIdFor(OrderStatus::class)->index()->constrained()->restrictOnDelete();
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
