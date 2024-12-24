<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusEnumTeamMembersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->enum('status', ['not started', 'in progress', 'completed'])
                  ->default('not started')
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->enum('status', ['not started', 'in progress', 'halfway through', 'almost done', 'completed'])
                  ->default('not started')
                  ->change();
        });
    }
}
