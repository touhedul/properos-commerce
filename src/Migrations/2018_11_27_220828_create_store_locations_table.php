<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label',255)->nullable();
            $table->string('address',255)->nullable();
            $table->string('city',100)->nullable();
            $table->string('state',100)->nullable();
            $table->string('country',100)->nullable();
            $table->string('zip',20)->nullable();
            $table->decimal('tax',10,2)->nullable()->default(0);
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
        Schema::dropIfExists('store_locations');
    }
}
