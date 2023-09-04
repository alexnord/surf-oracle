<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Tide extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'uuid',
        'timestamp',
        'type',
        'height',
        'noaa_station_id',
    ];

    /**
     * Get the NOAA station that the tide belongs to.
     */
    public function noaaStation()
    {
        return $this->belongsTo(NoaaStation::class);
    }
}
