<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Swell extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'location_id',
        'timestamp',
        'surfline_spot_id',
        'surfline_surf_height_min',
        'surfline_surf_height_max',
        'surfline_score',
        'surfline_human_relation',
        'surfline_swell_1_height',
        'surfline_swell_1_period',
        'surfline_swell_1_impact',
        'surfline_swell_1_power',
        'surfline_swell_1_direction',
        'surfline_swell_1_direction_min',
        'surfline_swell_1_optimal_score',
        'surfline_swell_2_height',
        'surfline_swell_2_period',
        'surfline_swell_2_impact',
        'surfline_swell_2_power',
        'surfline_swell_2_direction',
        'surfline_swell_2_direction_min',
        'surfline_swell_2_optimal_score',
        'surfline_swell_3_height',
        'surfline_swell_3_period',
        'surfline_swell_3_impact',
        'surfline_swell_3_power',
        'surfline_swell_3_direction',
        'surfline_swell_3_direction_min',
        'surfline_swell_3_optimal_score',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    /**
     * Get the location that owns the swell.
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
