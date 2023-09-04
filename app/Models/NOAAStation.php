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
        'noaa_id',
        'title',
        'lat',
        'lng',
        'timezone'
    ];

    /**
     * Get the tides for the NOAA station.
     */
    public function tides()
    {
        return $this->hasMany(Tide::class);
    }

    /**
     * Get the location that owns the NOAA station.
     */
    public function location()
    {
        return $this->hasMany(Location::class);
    }
    
}
