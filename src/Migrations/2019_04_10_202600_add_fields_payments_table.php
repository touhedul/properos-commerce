<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('refund_amount',10,2)->nullable()->after('fee')->default(0);
            $table->string('last_4')->nullable()->after('customer_email');
            $table->string('ref_transaction_id')->nullable()->after('transaction_id');
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
            $table->dropColumn('refund_amount');
            $table->dropColumn('last_4');
            $table->dropColumn('ref_transaction_id');
        });
    }
}
