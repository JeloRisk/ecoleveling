<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activity_log_books', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('activityName');
            $table->date('date');
            $table->time('time');
            $table->unsignedBigInteger('room_id');

            $table->timestamps();

            $table->foreign('room_id')->references('id')->on('rooms');
        });
    }




    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_log_books');
    }
};



/**
 * 
 * 
 */
