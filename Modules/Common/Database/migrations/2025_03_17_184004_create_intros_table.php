<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Common\App\Models\Intro;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('intros', function (Blueprint $table) {
            $table->id();
            $table->string('title_ar')->nullable();
            $table->string('title_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->string('image')->nullable();
            $table->foreignIdFor(Intro::class, 'parent_id')->nullable();
            $table->string('section');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intros');
    }
};
