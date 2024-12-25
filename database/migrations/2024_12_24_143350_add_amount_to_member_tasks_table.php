<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAmountToMemberTasksTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('member_tasks', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->default(0.00)->after('description')->comment('Task amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member_tasks', function (Blueprint $table) {
            $table->dropColumn('amount');
        });
    }
}