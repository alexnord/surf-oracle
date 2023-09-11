<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Swell;
use App\Models\Weather;
use App\Models\Tide;
use App\Models\User;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function getConditions(Request $request)
    {
        $user = auth()->user(); // Get the authenticated user

        $startTime = Carbon::createFromTimestampUTC($request->startTime);
        $endTime = Carbon::createFromTimestampUTC($request->endTime);
        $locationId = $request->locationId;

        $location = Location::findOrFail($locationId);

        $startSwell = Swell::where('location_id', $locationId)
            ->where('timestamp', '>=', $startTime)
            ->orderBy('timestamp', 'asc')
            ->first();

        $endSwell = Swell::where('location_id', $locationId)
            ->where('timestamp', '<=', $endTime)
            ->orderBy('timestamp', 'desc')
            ->first();

        $startWeather = Weather::where('location_id', $locationId)
            ->where('timestamp', '>=', $startTime)
            ->orderBy('timestamp', 'asc')
            ->first();

        $endWeather = Weather::where('location_id', $locationId)
            ->where('timestamp', '<=', $endTime)
            ->orderBy('timestamp', 'desc')
            ->first();

        // Get all tides for the session
        $startTideBefore = Tide::where('noaa_station_id', $location->noaa_station_id)
            ->where('timestamp', '<', $startTime)
            ->orderBy('timestamp', 'desc')
            ->first();

        $startTideAfter = Tide::where('noaa_station_id', $location->noaa_station_id)
            ->where('timestamp', '>', $startTime)
            ->orderBy('timestamp', 'asc')
            ->first();

        $endTideBefore = Tide::where('noaa_station_id', $location->noaa_station_id)
            ->where('timestamp', '<', $endTime)
            ->orderBy('timestamp', 'desc')
            ->first();

        $endTideAfter = Tide::where('noaa_station_id', $location->noaa_station_id)
            ->where('timestamp', '>', $endTime)
            ->orderBy('timestamp', 'asc')
            ->first();

        // Calculate start tide height and if it's rising or falling
        list($startTideHeight, $startTideChange) = $this->calculateTide($startTideBefore, $startTideAfter, $startTime);

        // Calculate end tide height and if it's rising or falling
        list($endTideHeight, $endTideChange) = $this->calculateTide($endTideBefore, $endTideAfter, $endTime);

        return response()->json([
            'start_swell' => $startSwell,
            'end_swell' => $endSwell,
            'start_weather' => $startWeather,
            'end_weather' => $endWeather,
            'start_tide_height' => $startTideHeight,
            'start_tide_change' => $startTideChange,
            'end_tide_height' => $endTideHeight,
            'end_tide_change' => $endTideChange,
        ]);
    }

    // This function calculates the tide heights and determines if they are rising or falling
    private function calculateTide($previousTide, $nextTide, $time)
    {
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





