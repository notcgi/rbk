<?php

namespace App\Services\Cbr;

interface CurrencyClientInterface
{
    /**
     * @param string $date
     * @return array<array{code:string,name:string,rate:float}>
     */
    public function rates(string $date): array;
}
