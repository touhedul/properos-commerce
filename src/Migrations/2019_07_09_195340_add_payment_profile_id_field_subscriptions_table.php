<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentProfileIdFieldSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->unsignedInteger('payment_profile_id')->nullable()->default(0)->after('price');
            $table->integer('days_to_pay')->nullable()->default(0)->after('next_payment_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('payment_profile_id');
            $table->dropColumn('days_to_pay');
        });
    }
}
