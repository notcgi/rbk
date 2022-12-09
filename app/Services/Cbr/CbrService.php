<?php

namespace App\Services\Cbr;

use App\Exceptions\CurrencyServiceException;
use Cache;
use Illuminate\Support\Carbon;

class CbrService implements CurrencyServiceInterface
{
    public function __construct(
        private CbrClient $client
    ) {}

    /**
     * @param string $base
     * @param string $to
     * @param Carbon $date
     * @return float
     */
    public function pairRate(string $base, string $to, Carbon $date): float
    {
        if ($base == $to) {
            $this->currencyRate($to, $date); // to throw Exception if currency not available
            return 1.0;
        } elseif ($base == 'RUR')
            return $this->currencyRate($to, $date);
        elseif ($to == 'RUR')
            return 1/$this->currencyRate($base, $date);
        else
            return $this->currencyRate($to, $date)/$this->currencyRate($base, $date);

    }

    public function currencyRate(string $currency, Carbon $date): float
    {
        $rates = $this->rates($date);
        $rates = array_values(array_filter($rates, fn($rate) => $rate['code'] == $currency));
        throw_if(empty($rates), new CurrencyServiceException('Unavailable currency', CurrencyServiceException::USER_ERROR));
        throw_if(count($rates)>1, new CurrencyServiceException('Internal Error',CurrencyServiceException::SERVER_ERROR));
        return $rates[0]['rate'];
    }

    /**
     * @param Carbon $date
     * @return array<array{code:string,name:string,rate:float}>
     */
    public function rates(Carbon $date): array
    {
        $date = $this->formatDate($date);
        return Cache::rememberForever("cbr.rates.$date", function () use ($date) {
            $result = $this->client->rates($date);
            return $this->formatRates($result);
        });
    }

    private function formatDate(Carbon $date): string
    {
        return $date->format('Y-m-d');
    }

    /**
     * @template  rate
     * @param array<array{Vname:string,Vnom:string,Vcurs:string,Vcode:string,VchCode:string}> $rates
     * @return array<array{code:string,name:string,rate:float}>
     */
    private function formatRates(array $rates): array
    {
        return array_map(fn($rate)=>[
            'code' => $rate['VchCode'],
            'name' => trim($rate['Vname']),
            'rate' => (float)$rate['Vcurs'],
        ], $rates);
    }
}
