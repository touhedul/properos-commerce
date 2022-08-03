<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTaxIdFieldStoreLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_locations', function (Blueprint $table) {
            $table->unsignedInteger('tax_id')->nullable()->default(0)->after('tax');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_locations', function (Blueprint $table) {
            $table->dropColumn('tax_id');
        });
    }
}
