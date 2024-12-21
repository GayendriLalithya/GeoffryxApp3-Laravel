<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('work_history', function (Blueprint $table) {
        $table->dropForeign(['professional_id']);
        $table->dropColumn('professional_id');

        $table->dropForeign(['rating_id']);
        $table->dropColumn('rating_id');
    });
}

public function down()
{
    Schema::table('work_history', function (Blueprint $table) {
        $table->unsignedBigInteger('professional_id');
        $table->unsignedBigInteger('rating_id');

        $table->foreign('professional_id')->references('professional_id')->on('professionals');
        $table->foreign('rating_id')->references('rating_id')->on('rating');
    });
}

};
