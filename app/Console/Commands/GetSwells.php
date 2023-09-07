<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Location;
use App\Models\Swell;

class GetSwells extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:swells';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get swell data from Surfline API.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get all active locations
        $locations = Location::where('active', true)->get();

        $bar = $this->output->createProgressBar(count($locations));

        // Loop through each location
        foreach ($locations as $location) {

            // Output some information to the command line
            $this->info("\n");
            $this->info("Scraping swell data for {$location->title}.");

            // Get the swell data for this location
            $this->getSurflineSwells($location);

            // Advance the progress bar
            $bar->advance();
        }

        $bar->finish();
        $this->info("\n");
        $this->info("Scraped and stored swell data for each location.");
    }

    /**
     * Get and store Surfline swell data.
     *
     * @param Location $location
     * @return mixed
     */
    private function getSurflineSwells(Location $location) {
        $baseUrl = "https://services.surfline.com/kbyg/spots/forecasts/wave";

        // Construct the URI for the API request
        $uri = "{$baseUrl}?spotId={$location->surfline_spot_id}&days=16&intervalHours=1&cacheEnabled=true&units%5BswellHeight%5D=FT&units%5BwaveHeight%5D=FT&accesstoken=610e362a5140a0976b09e0c9841de34d32629dd9";

        // Try to make the API request
        try {
            $response = Http::get($uri);
        } catch(\Exception $e) {
            // If there's an error, output it to the command line and return
            $this->error($e->getMessage());
            return;
        }

        // Parse the JSON response
        $contents = $response->json();

        // Loop through each hourly forecast
        foreach ($contents['data']['wave'] as $wave) {
            // Extract the necessary data
            $data = [
                'timestamp' => \Carbon\Carbon::createFromTimestamp($wave['timestamp']),
                'surfline_surf_height_min' => $wave['surf']['min'],
                'surfline_surf_height_max' => $wave['surf']['max'],
                'surfline_score' => $wave['surf']['optimalScore'],
                'surfline_human_relation' => $wave['surf']['humanRelation'],
                'location_id' => $location->id,
            ];

            // Loop through each swell
            for ($i = 1; $i < 4; $i++) {
                if (isset($wave['swells'][$i])) {
                    $swell = $wave['swells'][$i];
                    $data["surfline_swell_{$i}_height"] = $swell['height'];
                    $data["surfline_swell_{$i}_period"] = $swell['period'];
                    $data["surfline_swell_{$i}_impact"] = $swell['impact'];
                    $data["surfline_swell_{$i}_power"] = $swell['power'];
                    $data["surfline_swell_{$i}_direction"] = $swell['direction'];
                    $data["surfline_swell_{$i}_direction_min"] = $swell['directionMin'];
                    $data["surfline_swell_{$i}_optimal_score"] = $swell['optimalScore'];
                }
            }
            // dd($data);

            // Update or create a new Swell entry with the data
            Swell::updateOrCreate(
                [
                    'timestamp' => \Carbon\Carbon::createFromTimestamp($data['timestamp']),
                    'location_id' => $location->id,
                    'surfline_spot_id' => $location->surfline_spot_id,
                ],
                $data
            );
        }
    }
}