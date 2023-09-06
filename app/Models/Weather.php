<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Weather extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'timestamp',
        'location_id',
        'text',
        'icon',
        'temperature',
        'angle',
        'speed',
        'direction',
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
    public function location()
    {
        return $this->hasOne(Location::class, 'location_id');
    }
}
