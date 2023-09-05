<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
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
    
}
