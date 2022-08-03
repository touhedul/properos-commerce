<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateShippingMethodsTableAddStatusField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->boolean('enabled')->default(0)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->dropColumn('enabled');
        });
    }
}
