<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\NoaaStation;
use App\Models\Tide;
use GuzzleHttp\Client;

class ScrapeTideData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:tides';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape tide predictions from NOAA tide and currents API.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $stations = NoaaStation::where('active', true)->get();

        $bar = $this->output->createProgressBar(count($stations));

        foreach ($stations as $station) {

            $this->info("\nScraping tide info for {$station->title}.");

            $lastRetrieval = Tide::where('noaa_station_id', $station->id)
                ->orderBy('timestamp', 'desc')->first();

            $startDate = $lastRetrieval->timestamp ?? now();
            $endDate = $startDate->copy()->addDays(30);

            $this->info("Beginning at: {$startDate}.");

            try {
                $url = config('apis.tides') . '&begin_date=' . $startDate->format('Ymd') . '&end_date=' . $endDate->format('Ymd') . '&station=' . $station->noaa_id;
                $client = new Client();
                $res = $client->request('GET', $url);
            } catch(\Exception $e) {
                $this->error($e->getMessage());
                return;
            }

            $contents = json_decode($res->getBody());

            $predCount = count($contents->predictions);
            $this->info("\n{$predCount} predictions found.");

            foreach ($contents->predictions as $content) {

                $timestamp = Carbon::parse($content->t);

                // Skip duplicates
                if (Tide::where('timestamp', $timestamp)->where('noaa_station_id', $station->id)->exists()) {
                    continue;
                }

                try {
                    Tide::updateOrCreate([
                        'timestamp' => $timestamp,
                        'type' => $content->type,
                        'height' => $content->v,
                        'noaa_station_id' => $station->id,
                    ]);
                } catch(\Exception $e) {
                    $this->error($e->getMessage());
                    continue;
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->info("\nScraped and stored tide data for each station.\n");
    }
}
