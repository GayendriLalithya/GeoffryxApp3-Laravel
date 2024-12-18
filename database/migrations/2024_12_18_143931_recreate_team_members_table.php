<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecreateTeamMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Drop the existing team_members table if it exists
        Schema::dropIfExists('team_members');

        // Create the new team_members table
        Schema::create('team_members', function (Blueprint $table) {
            $table->id('team_member_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('team_id');
            $table->enum('status', ['not stated', 'in progress', 'halfway through', 'almost done', 'completed'])
                  ->default('not stated');
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users');
            $table->foreign('team_id')->references('team_id')->on('team');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop the team_members table
        Schema::dropIfExists('team_members');
    }
}
