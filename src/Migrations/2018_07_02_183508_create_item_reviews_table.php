<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('item_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('fullname',100)->nullable();
            $table->string('title',50)->nullable();
            $table->text('comment');
            $table->decimal('rate',10,2)->nullable()->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_reviews');
    }
}
