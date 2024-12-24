<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('member_tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('team_id')->after('member_task_id'); // Add the team_id column
            $table->dropColumn('name'); // Remove the name column

            $table->foreign('team_id')->references('team_id')->on('team'); // Add foreign key constraint
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member_tasks', function (Blueprint $table) {
            $table->dropForeign(['team_id']); // Remove foreign key constraint
            $table->dropColumn('team_id'); // Drop the team_id column
            $table->string('name')->after('member_task_id'); // Add the name column back
        });
    }
};
