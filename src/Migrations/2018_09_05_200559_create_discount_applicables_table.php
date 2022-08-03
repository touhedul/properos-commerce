<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountApplicablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_applicables', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('discount_id')->nullable();
            $table->string('discount_code',50)->nullable();
            $table->unsignedInteger('applicable_id')->nullable();
            $table->string('applicable_type',50)->nullable();
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
        Schema::dropIfExists('discount_applicables');
    }
}
