<?php

namespace App\Services\Cbr;

use SoapClient;

class CbrClient implements CurrencyClientInterface
{
    private SoapClient $client;

    public function __construct()
    {
        $this->client = new SoapClient(
            route('cbr.daily.info',['WSDL']),
            [
                'soap_version' => SOAP_1_2
            ]
        );
    }

    /**
     * @param string $date
     * @return  array<array{Vname:string,Vnom:string,Vcurs:string,Vcode:string,VchCode:string}> $rates
     */
    public function rates(string $date): array
    {
        try {

            $result = $this->client->GetCursOnDate([
                "On_date" => $date
            ]);
            $result = simplexml_load_string($result->GetCursOnDateResult->any);
            return json_decode(json_encode($result), true)['ValuteData']['ValuteCursOnDate'];
        } catch (\Exception $exception) {
            throw new \Exception('CBR Server Error', previous: $exception);
        }
    }
}
