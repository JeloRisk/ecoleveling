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
        Schema::create('room_occupancy_log_books', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->time('startTime');
            $table->time('endTime');
            $table->unsignedBigInteger('room_id');
            $table->decimal('usageMinutes');
            $table->string('status');
            $table->string('location');
            $table->timestamps();

            // Define foreign key constraint
            $table->foreign('room_id')->references('id')->on('rooms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_occupancy_log_books');
    }
};
