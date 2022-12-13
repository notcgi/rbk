<?php

namespace App\Services\Cbr;


use Illuminate\Support\Carbon;

interface CurrencyClientInterface
{
    /**
     * @param Carbon $date
     * @return array<array{code:string,name:string,rate:float}>
     */
    public function rates(Carbon $date): array;
}
