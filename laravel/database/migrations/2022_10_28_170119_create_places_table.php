<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->unsignedBigInteger('file_id');
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('visibility_id');
            $table->float("longitude");
            $table->float("latitude");
            $table->timestamps();
            $table->foreign('file_id')->references('id')->on('files');
            $table->foreign('author_id')->references('id')->on('users');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('places');
    }
};
