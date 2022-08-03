<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('payable_type');
            $table->Integer('payable_id');
            $table->string('type');
            $table->Integer('user_id');
            $table->string('operation');
            $table->decimal('amount')->default('0.00')->nullable();
            $table->decimal('fee')->default('0.00')->nullable();
            $table->string('account_type');
            $table->string('account_id');
            $table->string('description');
            $table->string('transaction_id');
            $table->string('transaction_status');
            $table->string('provider');
            $table->timestamps();
            $table->SoftDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
