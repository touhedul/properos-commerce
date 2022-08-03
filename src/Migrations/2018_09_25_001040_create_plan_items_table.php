<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',250)->nullable();
            $table->unsignedInteger('plan_id')->nullable();
            $table->unsignedInteger('item_id')->nullable();
            $table->integer('qty')->nullable();
            $table->decimal('price',10,2)->nullable();
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
        Schema::dropIfExists('plan_items');
    }
}
