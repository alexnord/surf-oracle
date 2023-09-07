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
        Schema::create('weather', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->dateTime('timestamp');
            $table->string('timezone')->default('UTC');
            $table->integer('location_id')->unsigned()->index('w_location_id');
            $table->foreign('location_id')->references('id')->on('locations');
            $table->string('text');
            $table->string('icon');
            $table->string('temperature');
            $table->string('angle');
            $table->string('direction')->nullable();
            $table->float('speed', 4, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather');
    }
};
