<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Location;
use App\Models\Weather;

// Define the GetWeather command class
class GetWeather extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // Define the command signature
    protected $signature = 'data:weather';

    /**
     * The console command description.
     *
     * @var string
     */
    // Define the command description
    protected $description = 'Get weather data via the Azure Maps API.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    // Define the handle method which is the main method that will be executed when the command is called
    public function handle()
    {
        // Get all active locations
        $locations = Location::where('active', true)->get();

        $bar = $this->output->createProgressBar(count($locations));

        // Loop through each location
        foreach ($locations as $location) {

            // Output some information to the command line
            $this->info("\n");
            $this->info("Scraping wind data for {$location->title}.");

            // Get the weather data for this location
            $this->getAzureMapsWeather($location);

            // Advance the progress bar
            $bar->advance();
        }

        $bar->finish();
        $this->info("\n");
        $this->info("Scraped and stored wind data for each location.");
    }

    /**
     * Get and store Azure Maps weather data.
     *
     * @param Location $location
     * @return mixed
     */
    private function getAzureMapsWeather(Location $location) {
        $baseUrl = config('apis.weather.azuremaps.url');

        // Construct the URI for the API request
        $uri = "https://{$baseUrl}/weather/forecast/hourly/json?api-version=1.0&query={$location->lat},{$location->lng}&unit=imperial&duration=240";

        // Try to make the API request
        try {
            $response = Http::withHeaders([
                'x-ms-client-id' => config('apis.weather.azuremaps.clientId'),
                'subscription-key' => config('apis.weather.azuremaps.subscriptionKey'),
            ])->get($uri);
        } catch(\Exception $e) {
            // If there's an error, output it to the command line and return
            $this->error($e->getMessage());
            return;
        }

        // Parse the JSON response
        $contents = $response->json();

        // Loop through each hourly forecast
        foreach ($contents['forecasts'] as $hour) {
            // Extract the necessary data
            $data = [
                // 'timezone_offset' => Carbon::parse($hour['date'])->offsetHours, // To store the offset
                // 'timestamp' => now()->setTimestamp(strtotime($hour['date'])),
                'timestamp' => Carbon::parse($hour['date'])->setTimezone('UTC'), // To store in UTC
                `timezone` => 'UTC',
                'location_id' => $location->id,
                'text' => $hour['iconPhrase'],
                'icon' => $hour['iconCode'],
                'temperature' => $hour['temperature']['value'],
                'angle' => $hour['wind']['direction']['degrees'],
                'speed' => $hour['wind']['speed']['value'],
                'direction' => $hour['wind']['direction']['localizedDescription'],
            ];

            // Update or create a new Weather entry with the data
            Weather::updateOrCreate(
                [
                    'timestamp' => $data['timestamp'],
                    'location_id' => $data['location_id'],
                ],
                $data
            );
        }
    }
}
