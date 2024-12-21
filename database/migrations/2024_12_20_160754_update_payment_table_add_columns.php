<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('payment', function (Blueprint $table) {
        $table->dropForeign(['installment_plan_id']);
        $table->dropColumn('installment_plan_id');

        $table->unsignedBigInteger('work_id')->after('payment_id');
        $table->unsignedBigInteger('user_id')->after('work_id');

        $table->foreign('work_id')->references('work_id')->on('work');
        $table->foreign('user_id')->references('user_id')->on('users');
    });
}

public function down()
{
    Schema::table('payment', function (Blueprint $table) {
        $table->dropForeign(['work_id']);
        $table->dropForeign(['user_id']);
        $table->dropColumn('work_id');
        $table->dropColumn('user_id');

        $table->unsignedBigInteger('installment_plan_id');
        $table->foreign('installment_plan_id')->references('installment_plan_id')->on('installment_plan');
    });
}

};
