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
        Schema::table('verify', function (Blueprint $table) {
            $table->string('status')->default('pending'); // Add status column with default value 'pending'
        });
    }
    
    public function down()
    {
        Schema::table('verify', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

};
