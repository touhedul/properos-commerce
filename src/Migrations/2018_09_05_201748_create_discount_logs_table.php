<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_logs', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('discount_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('discount_code')->nullable();
            $table->string('action')->nullable();
            $table->json('discount_data')->nullable();

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
        Schema::dropIfExists('discount_logs');
    }
}
