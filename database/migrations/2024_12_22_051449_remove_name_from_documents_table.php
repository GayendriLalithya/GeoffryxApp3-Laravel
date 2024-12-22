<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveNameFromDocumentsTable extends Migration
{
    public function up()
    {
        Schema::table('document', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    public function down()
    {
        Schema::table('document', function (Blueprint $table) {
            $table->string('name');
        });
    }
}
