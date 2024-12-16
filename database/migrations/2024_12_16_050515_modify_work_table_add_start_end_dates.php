<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyWorkTableAddStartEndDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('work', function (Blueprint $table) {
            $table->dropColumn('due_date');  // Removing the due_date column
            $table->date('start_date')->after('budget');  // Adding the start_date column
            $table->date('end_date')->after('start_date');  // Adding the end_date column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work', function (Blueprint $table) {
            $table->date('due_date')->after('budget');  // Restoring the due_date column
            $table->dropColumn(['start_date', 'end_date']);  // Dropping the new columns
        });
    }
}
