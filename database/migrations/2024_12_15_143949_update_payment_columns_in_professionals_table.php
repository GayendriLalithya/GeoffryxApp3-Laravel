<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePaymentColumnsInProfessionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('professionals', function (Blueprint $table) {
            $table->decimal('payment_min', 13, 2)->nullable()->change();
            $table->decimal('payment_max', 13, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('professionals', function (Blueprint $table) {
            // Revert back to the original settings if needed
            $table->decimal('payment_min', 8, 2)->nullable()->change();
            $table->decimal('payment_max', 8, 2)->nullable()->change();
        });
    }
}
