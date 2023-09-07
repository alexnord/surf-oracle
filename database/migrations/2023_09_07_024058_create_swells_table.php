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
        Schema::create('swells', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->dateTime('timestamp')->nullable();
            $table->string('timezone')->default('UTC');
            $table->integer('location_id')->unsigned()->index('w_location_id');
            $table->foreign('location_id')->references('id')->on('locations');
            $table->string('surfline_spot_id');
            $table->float('surfline_surf_height_min', 8, 5);
            $table->float('surfline_surf_height_max', 8, 5);
            $table->integer('surfline_score');
            $table->string('surfline_human_relation');

            $table->float('surfline_swell_1_height', 8, 5);
            $table->integer('surfline_swell_1_period');
            $table->float('surfline_swell_1_impact', 8, 5);
            $table->float('surfline_swell_1_power', 8, 5);
            $table->float('surfline_swell_1_direction', 8, 5);
            $table->float('surfline_swell_1_direction_min', 8, 5);
            $table->integer('surfline_swell_1_optimal_score');

            $table->float('surfline_swell_2_height', 8, 5);
            $table->integer('surfline_swell_2_period');
            $table->float('surfline_swell_2_impact', 8, 5);
            $table->float('surfline_swell_2_power', 8, 5);
            $table->float('surfline_swell_2_direction', 8, 5);
            $table->float('surfline_swell_2_direction_min', 8, 5);
            $table->integer('surfline_swell_2_optimal_score');

            $table->float('surfline_swell_3_height', 8, 5);
            $table->integer('surfline_swell_3_period');
            $table->float('surfline_swell_3_impact', 8, 5);
            $table->float('surfline_swell_3_power', 8, 5);
            $table->float('surfline_swell_3_direction', 8, 5);
            $table->float('surfline_swell_3_direction_min', 8, 5);
            $table->integer('surfline_swell_3_optimal_score');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('swells');
    }
};
