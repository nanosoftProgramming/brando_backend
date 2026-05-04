<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Client\App\Models\Client;
use Modules\Order\App\Models\Order;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Order::class)->index()->constrained()->restrictOnDelete();
            $table->foreignIdFor(Client::class)->index()->constrained()->restrictOnDelete();
            $table->unsignedTinyInteger('rate');
            $table->unsignedTinyInteger('restaurant_rate')->nullable();
            $table->text('comment')->nullable();
            $table->text('restaurant_comment')->nullable();

            $table->unique(['order_id', 'client_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rates');
    }
};
