<?php

namespace App\src\Application;

use DateTime;

interface CurrencyClientInterface
{
    /**
     * @param DateTime $date
     * @return array<array{code:string,name:string,rate:float}>
     */
    public function rates(DateTime $date): array;
}
