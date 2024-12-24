<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusEnumMemberTasksTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('member_tasks', function (Blueprint $table) {
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
        Schema::table('member_tasks', function (Blueprint $table) {
            $table->enum('status', ['not started', 'in progress', 'done']) // Revert to original values
                  ->default('not started')
                  ->change();
        });
    }
}
