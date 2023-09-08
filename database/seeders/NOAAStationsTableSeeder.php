<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NOAAStationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('noaa_stations')->insert([
            [
                'uuid' => (string) \Illuminate\Support\Str::uuid(),
                'active' => 1,
                'noaa_id' => '9410777',
                'title' => 'El Segundo',
                'lat' => '33.9083330',
                'lng' => '-118.4333330',
                'timezone' => 'America/Los_Angeles',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => (string) \Illuminate\Support\Str::uuid(),
                'active' => 1,
                'noaa_id' => '9410840',
                'title' => 'Santa Monica Pier',
                'lat' => '34.0083330',
                'lng' => '-118.5000000',
                'timezone' => 'America/Los_Angeles',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => (string) \Illuminate\Support\Str::uuid(),
                'active' => 1,
                'noaa_id' => '9411189',
                'title' => 'Ventura',
                'lat' => '34.2666670',
                'lng' => '-119.2833330',
                'timezone' => 'America/Los_Angeles',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => (string) \Illuminate\Support\Str::uuid(),
                'active' => 1,
                'noaa_id' => '9411270',
                'title' => 'Rincon Island',
                'lat' => '34.3483330',
                'lng' => '-119.4433330',
                'timezone' => 'America/Los_Angeles',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
