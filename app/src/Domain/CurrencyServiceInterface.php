<?php

namespace App\src\Domain;


use DateTime;

interface CurrencyServiceInterface
{
    /**
     * @param DateTime $date
     * @return array<array{code:string,name:string,rate:float}>
     */
    public function rates(DateTime $date): array;

    /**
     * @param string $currency
     * @param DateTime $date
     * @return float
     */
    public function currencyRate(string $currency, DateTime $date): float;

    /**
     * @param string $base
     * @param string $to
     * @param DateTime $date
     * @return float
     */
    public function pairRate(string $base, string $to, DateTime $date):float;
}
