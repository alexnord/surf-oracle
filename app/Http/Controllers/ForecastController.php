<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Swell;
use App\Models\Weather;
use App\Models\Tide;
use Carbon\Carbon;
use App\Http\Resources\LocationResource;
use App\Http\Resources\SwellResource;
use App\Http\Resources\WeatherResource;
use App\Http\Resources\TideResource;

class ForecastController extends Controller
{
    public function show($slug)
    {
        $location = Location::where('slug', $slug)->firstOrFail();

        // Get the UTC start and end times for 7 days
        list($utcStart, $utcEnd) = $this->getUtcTimes($location->timezone, 7);

        // Initialize the data array
        $data = [];

        // Loop through each day
        for ($day = 0; $day < 7; $day++) {
            // Calculate the start and end times for the current day
            $dayStart = $utcStart->copy()->addDays($day);
            $dayEnd = $utcStart->copy()->addDays($day + 1);

            // Get the tides within the start time and end time, as well as the record
            // that comes before the start time, and the record that comes after the end time
            $tides = $this->getTides($location, $dayStart, $dayEnd);

            // Get the swell, weather, and tide data and group them by hour block
            $dayData = [];
            for ($time = $dayStart; $time < $dayEnd; $time->addHour()) {

                $swells = Swell::where('location_id', $location->id)
                    ->whereBetween('timestamp', [$time, $time->copy()->addHour()])
                    ->orderBy('timestamp', 'asc')
                    ->first();

                $weather = Weather::where('location_id', $location->id)
                    ->whereBetween('timestamp', [$time, $time->copy()->addHour()])
                    ->orderBy('timestamp', 'asc')
                    ->first();

                // Calculate tide height and if it's rising or falling
                list($tideHeight, $tideChange) = $this->calculateTide($tides, $time);

                $dayData[] = [
                    'timestamp_utc' => $time->copy()->setTimezone('UTC')->toDateTimeString(),
                    'local_time' => $time->copy()->setTimezone($location->timezone)->toDateTimeString(),
                    'tide_height' => $tideHeight,
                    'tide_change' => $tideChange,
                    'swells' => $swells ? new SwellResource($swells) : null,
                    'weather' => $weather ? new WeatherResource($weather) : null,
                    'tides' => $tides ? TideResource::collection($tides) : null,
                ];
            }

            $data[] = [
                'day' => $dayStart->copy()->subDay()->setTimezone($location->timezone)->toDateString(), // Subtract a day from the dayStart to match the data
                'data' => $dayData,
            ];
        }

        return response()->json([
            'location' => new LocationResource($location),
            'data' => $data,
        ]);
    }

    private function getUtcTimes($timezone)
    {
        // Get the current UTC time
        $utcNow = now();

        // Convert the UTC time to the location's timezone
        $localTime = $utcNow->setTimezone($timezone);

        // Set the time to 00:00
        $localTime->setTime(0, 0);
        
        // Convert the local time back to UTC
        $utcStart = $localTime->copy()->setTimezone('UTC');
        
        // Get the end of the day in the user's timezone
        $localEndOfDay = $localTime->copy()->endOfDay()->setTimezone($timezone);

        // Convert the end of the day time back to UTC
        $utcEnd = $localEndOfDay->copy()->setTimezone('UTC');

        return [$utcStart, $utcEnd];
    }

    private function getTides($location, $utcStart, $utcEnd)
    {
        // Get all tides for the day
        $tides = Tide::where('noaa_station_id', $location->noaa_station_id)
            ->whereBetween('timestamp', [$utcStart, $utcEnd])
            ->orderBy('timestamp', 'asc')
            ->get();

        // Get the record before the first
        $previousRecord = Tide::where('timestamp', '<', $utcStart)
            ->orderBy('timestamp', 'desc')
            ->first();

        // Get the record after the last
        $nextRecord = Tide::where('timestamp', '>', $utcEnd)
            ->orderBy('timestamp', 'asc')
            ->first();

        // Add the previous and next records to the tides collection
        if ($previousRecord) {
            $tides->prepend($previousRecord);
        }
        if ($nextRecord) {
            $tides->push($nextRecord);
        }

        return $tides;
    }
    
    // This function calculates the tide heights and determines if they are rising or falling
    private function calculateTide($tides, $time)
    {
        // Initialize previous and next tide variables
        $previousTide = null;
        $nextTide = null;
        
        // Loop through the tides to find the previous and next tide based on the current time
        foreach ($tides as $tide) {
            
            // If the tide's timestamp is greater than the current time, it is the next tide
            if (Carbon::parse($tide['timestamp']) > $time) {
                $nextTide = $tide;
                break;
            }
            
            // If the tide's timestamp is less than or equal to the current time, it is the previous tide
            $previousTide = $tide;
        }

        // Initialize tide height and tide change variables
        $tideHeight = 0;
        $tideChange = 'Unknown';
        
        // If both previous and next tides are found, calculate the tide height and determine if it's rising or falling
        if ($previousTide && $nextTide) {
            
            // Calculate the time elapsed since the previous tide
            $timeElapsed = $time->diffInSeconds(Carbon::parse($previousTide['timestamp']));
            
            // Calculate the total time between the previous and next tides
            $totalTime = Carbon::parse($nextTide['timestamp'])->diffInSeconds(Carbon::parse($previousTide['timestamp']));
            
            // Calculate the change in height between the previous and next tides
            $heightChange = $nextTide['height'] - $previousTide['height'];

            // Calculate the tide height at the current hour by adding the proportion of the height change to the previous tide's height
            $tideHeight = $previousTide['height'] + ($heightChange * ($timeElapsed / $totalTime));

            // Determine if the tide is rising or falling based on the height change
            $tideChange = $heightChange > 0 ? 'Rising' : 'Falling';

        } else if ($previousTide) {
            
            // If only the previous tide is found, the tide height is the height of the previous tide
            $tideHeight = $previousTide['height'];
        }

        // Return the calculated tide height and tide change
        return [$tideHeight, $tideChange];
    }
}
