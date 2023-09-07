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
        Schema::create('tides', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->dateTime('timestamp')->nullable();
            $table->string('timezone')->default('UTC');
            $table->string('type');
            $table->float('height', 4, 2);
            $table->integer('noaa_station_id')->unsigned()->nullable();
            $table->foreign('noaa_station_id')->references('id')->on('noaa_stations');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tides');
    }
};
