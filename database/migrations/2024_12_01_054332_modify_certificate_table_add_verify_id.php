<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyCertificateTableAddVerifyId extends Migration
{
    public function up()
    {
        Schema::table('certificate', function (Blueprint $table) {
            // Drop the old foreign key and user_id column
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            // Add the new foreign key column 'verify_id'
            $table->unsignedBigInteger('verify_id');

            // Add the foreign key constraint referencing the 'verify' table
            $table->foreign('verify_id')->references('verify_id')->on('verify')->onDelete('cascade');
        });
    }

    public function down()
    {
        // Reverse the changes in case of rollback
        Schema::table('certificate', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['verify_id']);

            // Drop the verify_id column
            $table->dropColumn('verify_id');

            // Re-add the old user_id column
            $table->unsignedBigInteger('user_id');

            // Add the old foreign key reference for user_id
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }
}
