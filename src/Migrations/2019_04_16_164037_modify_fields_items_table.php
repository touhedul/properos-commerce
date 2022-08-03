<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyFieldsItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            \DB::statement('ALTER TABLE `items` DROP COLUMN `box_unit`');
            \DB::statement('ALTER TABLE `items` DROP COLUMN `units_in_box`');
            \DB::statement('ALTER TABLE `items` CHANGE COLUMN `package_type` `package_type` JSON NULL DEFAULT NULL AFTER `width_unit`');
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
            $table->string('box_unit');
            $table->string('units_in_box');
        });
    }
}
