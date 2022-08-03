<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreFieldsSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->integer('total_payments')->default(0)->nullable()->after('payment_profile_id');
            $table->integer('payments_made')->default(0)->nullable()->after('total_payments');
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
            $table->dropColumn('total_payments');
            $table->dropColumn('payments_made');
        });
    }
}
