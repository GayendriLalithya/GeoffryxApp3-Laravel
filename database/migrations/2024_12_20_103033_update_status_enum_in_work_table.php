<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusEnumInWorkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('work', function (Blueprint $table) {
            // Modify the enum column to only allow 'not started', 'in progress', 'completed'
            $table->enum('status', ['not started', 'in progress', 'completed'])
                  ->default('not started')
                  ->change();
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
            // Revert to the original enum values
            $table->enum('status', ['not started', 'in progress', 'halfway through', 'almost done', 'completed'])
                  ->default('not started')
                  ->change();
        });
    }
}
