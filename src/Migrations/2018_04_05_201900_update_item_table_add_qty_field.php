<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateItemTableAddQtyField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->integer('total_qty')->after('price');
            $table->string('weight_unit')->after('weight')->nullable();
            $table->timestamp('publish_date')->after('status');
            $table->float('discount_percent', 8, 2)->after('price')->nullable();
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
            $table->dropColumn('total_qty');
            $table->dropColumn('publish_date');
            $table->dropColumn('weight_unit');
            $table->dropColumn('discount_percent');
        });
    }
}
