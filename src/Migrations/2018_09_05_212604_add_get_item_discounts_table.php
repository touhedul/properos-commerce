<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGetItemDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->unsignedInteger('get_item_id')->nullable()->default(0)->after('get_what');
            $table->string('get_item_name',100)->nullable()->after('get_item_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->dropColumn('get_item_id');
            $table->dropColumn('get_item_name');
        });
    }
}
