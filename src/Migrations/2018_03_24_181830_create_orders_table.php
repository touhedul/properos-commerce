<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0)->nullable();
            $table->decimal('total_amount', 8, 2)->default('0.0')->nullable();
            $table->decimal('paid_amount', 8, 2)->default('0.0')->nullable();
            $table->string('shipping_address1')->nullable();
            $table->string('shipping_address2')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_zip')->nullable();
            $table->string('shipping_country')->nullable();
            $table->integer('shipping_method')->default(0)->nullable();
            $table->string('shipping_urgency',50)->nullable();
            $table->string('shipping_tracking')->nullable();
            $table->decimal('shipping_cost', 8, 2)->nullable();
            $table->string('status')->default('pending')->nullable();
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
        Schema::dropIfExists('orders');
        Schema::table('orders', function ($table) {
            DB::statement('ALTER TABLE' . $table . 'ALTER COLUMN enabled DROP DEFAULT');
        });
    }
}
