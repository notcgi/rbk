<?php

namespace App\src\Infrastructure;

use App\Exceptions\CurrencyClientException;
use App\src\Domain\CurrencyClientInterface;
use DateTime;
use SoapClient;

class CbrClient implements CurrencyClientInterface
{
    private SoapClient $client;

    public function __construct()
    {
        try {
            $this->client = new SoapClient(
                route('cbr.daily.info',['WSDL']),
                [
                    'soap_version' => SOAP_1_2
                ]
            );
        } catch (\SoapFault $exception) {
            throw new CurrencyClientException(previous: $exception);
        }
    }

    /**
     * @param DateTime $date
     * @return array<array{code:string,name:string,rate:float}>
     * @throws \Exception
     */
    public function rates(DateTime $date): array
    {
        try {

            $result = $this->client->GetCursOnDate([
                "On_date" => $this->formatDate($date)
            ]);
            $result = simplexml_load_string($result->GetCursOnDateResult->any);
            $result = json_decode(json_encode($result), true)['ValuteData']['ValuteCursOnDate'];
            return $this->formatRates($result);
        } catch (\Exception $exception) {
            throw new CurrencyClientException(previous: $exception);
        }
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


    private function formatDate(DateTime $date): string
    {
        return $date->format('Y-m-d');
    }
}
