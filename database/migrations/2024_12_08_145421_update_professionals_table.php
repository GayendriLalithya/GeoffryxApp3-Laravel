<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('professionals', function (Blueprint $table) {
            // Remove 'payment_range' column
            $table->dropColumn('payment_range');

            // Remove 'account_status' column
            $table->dropColumn('account_status');

            // Add 'payment_min' and 'payment_max' columns
            $table->decimal('payment_min', 8, 2)->nullable();
            $table->decimal('payment_max', 8, 2)->nullable();

            // Add 'preferred_project_size' column with ENUM
            $table->enum('preferred_project_size', ['small', 'medium', 'large', 'all'])->default('all');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('professionals', function (Blueprint $table) {
            // Revert changes: Add the columns back
            $table->string('payment_range')->nullable();
            $table->enum('account_status', ['pending', 'approved', 'rejected'])->default('pending');
        
            // Drop newly added columns
            $table->dropColumn('payment_min');
            $table->dropColumn('payment_max');
            $table->dropColumn('preferred_project_size');
        });
    }
};
