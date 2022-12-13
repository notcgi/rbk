<?php

namespace App\Services\CurrencyService;

use Illuminate\Support\Carbon;

interface CurrencyServiceInterface
{
    /**
     * @param Carbon $date
     * @return array<array{code:string,name:string,rate:float}>
     */
    public function rates(Carbon $date): array;

    /**
     * @param string $currency
     * @param Carbon $date
     * @return float
     */
    public function currencyRate(string $currency, Carbon $date): float;

    /**
     * @param string $base
     * @param string $to
     * @param Carbon $date
     * @return float
     */
    public function pairRate(string $base, string $to, Carbon $date):float;
}
