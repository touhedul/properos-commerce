<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('discount_id')->nullable();
            $table->string('discount_code',50)->nullable();
            $table->string('country_name',100)->nullable();
            $table->string('country_code',20)->nullable();
            $table->string('state_name',100)->nullable();
            $table->string('state_abbr',20)->nullable();
            $table->string('city_name',100)->nullable();
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
        Schema::dropIfExists('discount_locations');
    }
}
