<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificate', function (Blueprint $table) {
            $table->id('certificate_id');  // Primary Key
            $table->unsignedBigInteger('user_id');  // Foreign Key referencing users table
            $table->string('certificate_name');  // Certificate name (e.g., 'Degree', 'License', etc.)
            $table->string('certificate');  // The actual certificate file (can store file path or base64 data)
            $table->timestamps();

            // Define the foreign key constraint
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('certificate');
    }
}
