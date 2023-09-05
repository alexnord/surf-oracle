<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class Tide extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'timestamp',
        'type',
        'height',
        'noaa_station_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    /**
     * Get the NOAA station that the tide belongs to.
     */
    public function noaaStation()
    {
        return $this->belongsTo(NoaaStation::class);
    }
}
