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
            $table->unsingedBigInteger('file_id')->autoincrement();

            $table->unsingedBigInteger('author_id')->autoincrement();
            $table->unsingedBigInteger('visibility_id')->autoincrement();
            $table->unsingedBigInteger('category_id:')->autoincrement();
            $table->timestamps();

            $table->float("longitude");
            $table->float("latitude");
            
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
