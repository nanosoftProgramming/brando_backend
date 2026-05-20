<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Product\App\Models\Attribute;
use Modules\Product\App\Models\Product;

class CreateProductAttributeValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Attribute::class)->index()->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('attribute_value_id');
            $table->unsignedDouble('price_egp');
            $table->unsignedDouble('price_sar');
            $table->timestamps();

            $table->unique(['product_id', 'attribute_id', 'attribute_value_id'], 'pdt_att_val_unique');
            $table->foreign('attribute_value_id', 'pdt_att_values_att_value_id_fk')->references('id')->on('attribute_values')->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_attribute_values');
    }
}
