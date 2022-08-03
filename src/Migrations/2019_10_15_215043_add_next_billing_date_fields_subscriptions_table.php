<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNextBillingDateFieldsSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dateTime('next_billing_date')->after('next_payment_date')->nullable();
            $table->unsignedInteger('grace_days')->after('days_to_pay')->nullable()->default(0);
            $table->text('notes')->nullable()->after('data');

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
            $table->dropColumn('next_billing_date');
            $table->dropColumn('grace_days');
            $table->dropColumn('notes');
        });
    }
}
