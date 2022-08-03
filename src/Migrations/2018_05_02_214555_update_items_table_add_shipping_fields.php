<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateItemsTableAddShippingFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->float('cost')->after('price')->nullable();
            $table->string('barcode')->after('weight_unit')->nullable();
            $table->float('length')->after('barcode')->nullable();
            $table->string('length_unit')->after('barcode')->nullable();
            $table->float('height')->after('length_unit')->nullable();
            $table->string('height_unit')->after('length_unit')->nullable();
            $table->float('width')->after('height_unit')->nullable();
            $table->string('width_unit')->after('height_unit')->nullable();
            $table->string('package_type')->after('width_unit')->nullable();
            $table->string('units_in_box')->after('package_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('cost');
            $table->dropColumn('barcode');
            $table->dropColumn('length');
            $table->dropColumn('height');
            $table->dropColumn('width');
            $table->dropColumn('package_type');
            $table->dropColumn('units_in_box');
        });
    }
}
