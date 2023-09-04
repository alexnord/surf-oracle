<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory;
    use HasUuids;
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

    /**
     * Get the NOAA station that belongs to the location.
     */
    public function noaaStation()
    {
        return $this->belongsTo(NOAAStation::class, 'noaa_station_id');
    }
    
}
