<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOrdersTableAddNotePartialMinPaymentFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->longText('notes')->after('payment_method_id')->nullable();
            $table->boolean('partial')->after('notes')->default(0);
            $table->float('min_payment')->after('partial')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('notes');
            $table->dropColumn('partial');
            $table->dropColumn('min_payment');
        });
    }
}
