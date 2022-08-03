<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id')->nullable()->default(0);
            $table->string('code',50)->nullable();
            $table->enum('discount_type', ['percentage', 'fixed_amount', 'free_shipping', 'buy_x_get_y'])->nullable()->default('percentage');
            $table->decimal('discount_value', 10, 2)->nullable()->default(0);
            $table->unsignedInteger('buy_qty')->nullable()->default(0);
            $table->enum('apply_to', ['order', 'products', 'categories'])->nullable()->default('order');
            $table->unsignedInteger('get_qty')->nullable()->default(0);
            $table->enum('get_what', ['same', 'other'])->nullable()->default('same');
            $table->enum('min_requirement', ['none', 'purchase_amount', 'qty_items'])->nullable()->default('none');
            $table->decimal('requirement_amount',10,2)->nullable()->default(0);
            $table->enum('eligible_customers', ['everyone', 'specific', 'group'])->nullable()->default('everyone');
            $table->enum('eligible_locations', ['any', 'specific'])->nullable()->default('any');
            $table->unsignedInteger('usage_limit')->nullable()->default(0);
            $table->unsignedInteger('user_limit')->nullable()->default(0);
            $table->unsignedInteger('usage_count')->nullable()->default(0);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();

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
        Schema::dropIfExists('discounts');
    }
}
