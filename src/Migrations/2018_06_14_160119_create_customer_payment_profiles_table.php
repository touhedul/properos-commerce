<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerPaymentProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_payment_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_profile_id');
            $table->string('payment_profile_id');
            $table->string('last_numbers')->nullable();;
            $table->string('brand')->nullable();
            $table->boolean('default')->nullable();
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
        Schema::dropIfExists('customer_payment_profiles');
    }
}
