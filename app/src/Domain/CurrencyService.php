<?php

namespace App\src\Domain;

use App\src\Application\ClientCache;
use App\src\Application\CurrencyClientException;
use App\src\Application\CurrencyServiceException;
use DateTime;

class CurrencyService implements CurrencyServiceInterface
{
    public const BASE_CURRENCY = 'RUR';
    public function __construct(
        private ClientCache $client
    ) {}

    /**
     * @param string $base
     * @param string $to
     * @param DateTime $date
     * @return float
     */
    public function pairRate(string $base, string $to, DateTime $date): float
    {
        if ($base == $to) {
            $this->currencyRate($to, $date); // to throw Exception if currency not available
            return 1.0;
        } elseif ($base == self::BASE_CURRENCY)
            return $this->currencyRate($to, $date);
        elseif ($to == self::BASE_CURRENCY)
            return 1/$this->currencyRate($base, $date);
        else
            return $this->currencyRate($to, $date)/$this->currencyRate($base, $date);
    }

    public function currencyRate(string $currency, DateTime $date): float
    {
        $rates = $this->rates($date);

        $rates = array_values(array_filter($rates, fn($rate) => $rate['code'] == $currency));

        throw_if(empty($rates), new CurrencyServiceException('Unavailable currency'));
        throw_if(count($rates)>1, new CurrencyClientException());

        return $rates[0]['rate'];
    }

    /**
     * @param DateTime $date
     * @return array<array{code:string,name:string,rate:float}>
     */
    public function rates(DateTime $date): array
    {
        return $this->client->rates($date);
    }

    /**
     * Map to get previous trading day (skipping weekend)
     */
    private const PREVIOUS_DAY_MAP = [
        1=>3,
        2=>1,
        3=>1,
        4=>1,
        5=>1,
        6=>1,
        7=>2,
    ];
    public function previousDate(DateTime $date): DateTime
    {
        $date = clone $date;
        $subDays = self::PREVIOUS_DAY_MAP[(int)$date->format('N')];
        return $date->modify("-$subDays day");
    }
}
