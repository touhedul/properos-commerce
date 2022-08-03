<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePaymentErrorsTableAddCustomerEmailField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_errors', function (Blueprint $table) {
            $table->string('user_id')->nullable()->change();
            $table->string('customer_email')->after('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_errors', function (Blueprint $table) {
            $table->string('user_id')->change();
            $table->dropColumn('customer_email');
        });
    }
}
