<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('order_id')->default(0)->nullable();
            $table->Integer('item_id')->default(0)->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->Integer('sku_id')->default(0)->nullable();
            $table->Integer('qty')->default(0)->nullable();
            $table->decimal('price',8,2)->default('0.0')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
