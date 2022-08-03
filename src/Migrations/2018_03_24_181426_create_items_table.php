<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('account_id')->default(0)->nullable();
            $table->string('name');
            $table->string('description');
            $table->Integer('category_id')->default(0)->nullable();
            $table->decimal('price',8,2)->default('0.0')->nullable();
            $table->decimal('msrp',8,2)->default('0.0')->nullable();
            $table->string('mpn')->default('')->nullable();
            $table->string('um')->default('unit')->nullable();
            $table->decimal('weight',6,2)->default('0.0')->nullable();
            $table->Integer('last_sku')->default(0)->nullable();
            $table->string('status')->default('active')->nullable();
            $table->timestamps();
			$table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
