<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReviewsFieldsItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->unsignedInteger('reviews_total')->nullable()->default(0)->after('publish_date');
            $table->unsignedInteger('reviews_percent')->nullable()->default(0)->after('reviews_total');
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
            $table->dropColumn('reviews_total');
            $table->dropColumn('reviews_percent');
        });
    }
}
