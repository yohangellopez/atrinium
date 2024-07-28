<?php

namespace App\Console\Commands;

use App\Models\HistoricalRate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportHistoricalRates extends Command
{
    protected $signature = 'import:historical-rates';
    protected $description = 'Import historical currency exchange rates';

    private $url = 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-hist.xml';
    public function handle()
    {
        $response = Http::get($this->url);

        if ($response->failed()) {
            $this->error('Failed to fetch exchange rates.');
            return;
        }

        $xml = simplexml_load_string($response->body());

        foreach ($xml->Cube->Cube as $day) {
            $date = (string) $day['time'];

            foreach ($day->Cube as $rate) {
                $currency = (string) $rate['currency'];
                $rateValue = (float) $rate['rate'];

                HistoricalRate::updateOrCreate(
                    ['date' => $date, 'currency' => $currency],
                    ['rate' => $rateValue]
                );
            }
        }

        $this->info('Historical rates imported successfully.');
    }
}
