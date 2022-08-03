<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCustomerPaymentProfilesTableAddExpDateField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_payment_profiles', function (Blueprint $table) {
            $table->string('expiration_date')->after('last_numbers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_payment_profiles', function (Blueprint $table) {
            $table->dropColumn('expiration_date');
        });
    }
}
