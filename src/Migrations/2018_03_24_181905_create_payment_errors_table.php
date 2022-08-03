<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentErrorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_errors', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('user_id')->default(0)->nullable();
            $table->Integer('order_id');
            $table->string('ip_address',50)->nullable();
			$table->text('request_data')->nullable();
			$table->text('error_response')->nullable();
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
        Schema::dropIfExists('payment_errors');
    }
}
