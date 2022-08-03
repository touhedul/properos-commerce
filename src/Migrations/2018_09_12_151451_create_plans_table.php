<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 50);
            $table->text('description')->nullable();
            $table->float('price')->nullable()->default(0);
            $table->unsignedTinyInteger('interval_count')->nullable()->default(1);
            $table->enum('interval', ['day', 'week', 'month', 'year', 'contact_us'])->nullable()->default('month');
            $table->enum('status', ['private', 'public', 'cancelled'])->nullable()->default('public');
            $table->softDeletes();
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
        Schema::dropIfExists('plans');
    }
}
