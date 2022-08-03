<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->unsignedInteger('interval_count')->after('expires_at')->nullable();
            $table->string('interval', 50)->after('interval_count')->nullable();
            $table->decimal('price')->after('interval')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('interval_count');
            $table->dropColumn('interval');
            $table->dropColumn('price');
        });
    }
}
