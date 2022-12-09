<?php

namespace App\Services\Cbr;

interface CurrencyClientInterface
{
    public function rates(string $date): array;
}
