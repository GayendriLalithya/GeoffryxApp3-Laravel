<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWorkUserToWorkHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('work_history', function (Blueprint $table) {
            $table->unsignedBigInteger('work_id')->after('rating_id'); // Add the work_id column
            $table->unsignedBigInteger('user_id')->after('work_id'); // Add the user_id column
            
            $table->foreign('work_id')->references('work_id')->on('work'); // Setting up foreign key to work table
            $table->foreign('user_id')->references('user_id')->on('users'); // Setting up foreign key to users table
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work_history', function (Blueprint $table) {
            $table->dropForeign(['work_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['work_id', 'user_id']);
        });
    }
}
