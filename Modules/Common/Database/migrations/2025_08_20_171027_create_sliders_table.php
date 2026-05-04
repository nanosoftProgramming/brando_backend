<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Admin\App\Models\Admin;
use Modules\Restaurant\App\Models\Restaurant;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->foreignIdFor(Admin::class)->nullable()->index()->constrained()->nullOnDelete();
            $table->foreignIdFor(Restaurant::class)->nullable()->index()->constrained()->cascadeOnDelete();
            $table->boolean('is_active')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
