<?php

namespace App\Services\Cbr;

use App\Exceptions\CurrencyClientException;
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

        throw_if(empty($rates), new CurrencyServiceException('Unavailable currency'));
        throw_if(count($rates)>1, new CurrencyClientException());

        return $rates[0]['rate'];
    }

    /**
     * @param Carbon $date
     * @return array<array{code:string,name:string,rate:float}>
     */
    public function rates(Carbon $date): array
    {
        return Cache::rememberForever($this->getCacheKey($date), function () use ($date) {
            return $this->client->rates($date);
        });
    }

    private function getCacheKey(Carbon $date): string
    {
        return "cbr.rates.".$date->format('Y-m-d');
    }
}
