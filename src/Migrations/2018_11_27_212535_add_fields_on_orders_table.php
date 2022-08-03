<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsOnOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('subtotal',10,2)->default(0)->nullable()->after('total_amount');
            $table->decimal('tax_base',10,2)->default(0)->nullable()->after('subtotal');
            $table->decimal('tax',10,2)->default(0)->nullable()->after('tax_base');
            $table->unsignedInteger('store_id')->after('shipping_country')->nullable();
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
            $table->dropColumn('subtotal');
            $table->dropColumn('tax_base');
            $table->dropColumn('tax');
            $table->dropColumn('shipping_country');
        });
    }
}
