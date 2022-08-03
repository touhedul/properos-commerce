<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable()->index();
            $table->enum('status', ['expired', 'trial', 'active', 'locked'])->nullable()->default('trial');
            $table->dateTime('started_trial_at')->nullable();
		    $table->unsignedInteger('last_plan_id')->nullable()->index();
		    $table->unsignedInteger('current_plan_id')->nullable()->index();
		    $table->unsignedInteger('next_plan_id')->nullable()->index();
            $table->unsignedDecimal('last_payment')->nullable()->default(0);
            $table->dateTime('last_payment_date')->nullable();
            $table->dateTime('next_payment_date')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->json('data')->nullable();
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
        Schema::dropIfExists('subscriptions');
    }
}
