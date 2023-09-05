<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \DB::table('locations')->insert([
            [
                'uuid' => (string) \Illuminate\Support\Str::uuid(),
                'active' => 1,
                'title' => 'Point Dume',
                'slug' => 'point-dume',
                'lat' => '34.0012040',
                'lng' => '-118.8068260',
                'noaa_station_id' => \DB::table('noaa_stations')->where('title', 'Santa Monica Pier')->first()->id,
                'surfline_spot_id' => '5842041f4e65fad6a7708936',
                'timezone' => 'America/Los_Angeles',
            ],
            [
                'uuid' => (string) \Illuminate\Support\Str::uuid(),
                'active' => 1,
                'title' => 'Ventura Point',
                'slug' => 'ventura-point',
                'lat' => '34.2735810',
                'lng' => '-119.3029350',
                'noaa_station_id' => \DB::table('noaa_stations')->where('title', 'Ventura')->first()->id,
                'surfline_spot_id' => '584204204e65fad6a77096b1',
                'timezone' => 'America/Los_Angeles',
            ],
        ]);
    }
}
