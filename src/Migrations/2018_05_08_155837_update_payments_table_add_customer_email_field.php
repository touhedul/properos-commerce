<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePaymentsTableAddCustomerEmailField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('user_id')->nullable()->change();
            $table->string('account_type')->nullable()->change();
            $table->string('account_id')->nullable()->change();
            $table->string('description')->nullable()->change();
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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('user_id')->change();
            $table->string('account_type')->change();
            $table->string('account_id')->change();
            $table->string('description')->change();
            $table->dropColumn('customer_email');
        });
    }
}
