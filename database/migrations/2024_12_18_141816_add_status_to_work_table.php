<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToWorkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('work', function (Blueprint $table) {
            $table->enum('status', ['not started', 'in progress', 'halfway through', 'almost done', 'completed'])
                  ->default('not started')
                  ->after('end_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}

