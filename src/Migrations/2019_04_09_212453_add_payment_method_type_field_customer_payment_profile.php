<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentMethodTypeFieldCustomerPaymentProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_payment_profiles', function (Blueprint $table) {
            $table->string('payment_method_type')->after('payment_profile_id')->nullable();
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
            $table->dropColumn('payment_method_type');
        });
    }
}
