<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Location extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'active',
        'title',
        'slug',
        'image',
        'lat',
        'lng',
        'noaa_station_id',
        'buoy_id',
        'surfline_spot_id',
        'timezone',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    /**
     * Get the NOAA station that belongs to the location.
     */
    public function noaaStation()
    {
        return $this->belongsTo(NOAAStation::class, 'noaa_station_id');
    }

    /**
     * Get the swells that belong to the location.
     */
    public function swells()
    {
        return $this->hasMany(Swell::class);
    }

    /**
     * Get the weather that belongs to the location.
     */
    public function weather()
    {
        return $this->hasMany(Weather::class);
    }

    /**
     * Get the tides that belong to the location.
     */
    public function tides()
    {
        return $this->hasMany(Tide::class, 'noaa_station_id', 'noaa_station_id');
    }
}
