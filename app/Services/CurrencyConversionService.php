<?php

namespace App\Services;

use App\Models\CurrencyConversion;
use Illuminate\Support\Facades\Http;

class CurrencyConversionService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.fixer.key');
    }

    public function convert($from, $to, $amount)
    {
        // Verificar si la conversión ya existe
        $existingConversion = CurrencyConversion::where('from_currency', $from)
            ->where('to_currency', $to)
            ->where('amount', $amount)
            ->whereDate('date', now())
            ->first();

        if ($existingConversion) {
            return $existingConversion->converted_amount;
        }
        
        $response = Http::get("http://data.fixer.io/api/convert?access_key={$this->apiKey}&from={$from}&to={$to}&amount={$amount}");

        $data = $response->json();

        if (!$response->successful() || !isset($data['result'])) {
            throw new \Exception('Error en la conversión de moneda.'. $response->body());
        }

        // Guardar el resultado en la base de datos
        $conversion = CurrencyConversion::create([
            'from_currency'    => $from,
            'to_currency'      => $to,
            'amount'           => $amount,
            'converted_amount' => $data['result'],
            'date'             => now(),
        ]);

        return $conversion->converted_amount;
    }
}
