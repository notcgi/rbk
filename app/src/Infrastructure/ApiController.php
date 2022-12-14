<?php

namespace App\src\Infrastructure;

use App\src\Domain\CurrencyServiceInterface;
use DateTime;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ApiController
{
    public function __construct(
        private CurrencyServiceInterface $service
    ) {}

    public function rates()
    {
        $date = $this->getDateFromRequest();
        return $this->service->rates($date);
    }

    private function getDateFromRequest(): \DateTime
    {
        try {
            $date = new DateTime(request('date', 'today'));
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException('Incorrect date format');
        }
        if ($date > new DateTime())
            throw new UnprocessableEntityHttpException('Incorrect date');
        return $date->setTime(0,0);
    }

    public function pair(string $base, string $to)
    {
        $date = $this->getDateFromRequest();
        $previousDate= $this->service->previousDate($date);

        return [
            'rate' => $rate = $this->service->pairRate($base, $to, $date),
            'change' => round($rate - $this->service->pairRate($base, $to, $previousDate),13) // round to remove calculation error (0.000000000000001)
        ];
    }
}
