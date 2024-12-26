<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueConstraintToPendingProfessionalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pending_professional', function (Blueprint $table) {
            $table->unique(['user_id', 'professional_id', 'work_id'], 'unique_user_professional_work');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pending_professional', function (Blueprint $table) {
            $table->dropUnique('unique_user_professional_work'); // Use the same constraint name
        });
    }
}
