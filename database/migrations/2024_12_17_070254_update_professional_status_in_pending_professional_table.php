<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProfessionalStatusInPendingProfessionalTable extends Migration
{
    public function up()
    {
        // Modify the professional_status column to include new values
        Schema::table('pending_professional', function (Blueprint $table) {
            $table->enum('professional_status', ['pending', 'accepted', 'rejected', 'removed'])
                  ->default('pending')
                  ->change();
        });
    }

    public function down()
    {
        // Revert to the original enum values
        Schema::table('pending_professional', function (Blueprint $table) {
            $table->enum('professional_status', ['pending', 'accepted', 'referred'])
                  ->default('pending')
                  ->change();
        });
    }
}
