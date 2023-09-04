<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('noaa_stations', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->boolean('active')->default(1);
            $table->integer('noaa_id');
            $table->string('title');
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->string('timezone');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('noaa_stations');
    }
};
