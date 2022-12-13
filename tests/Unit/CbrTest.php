<?php

namespace Tests\Unit;

use App\Exceptions\CurrencyServiceException;
use App\Services\CurrencyService\CurrencyServiceInterface;
use Tests\TestCase;

class CbrTest extends TestCase
{
    private CurrencyServiceInterface $service;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->afterApplicationCreated(fn()=>$this->service = $this->app->make(CurrencyServiceInterface::class));
    }

    public function testRatesStructure()
    {
        $result = $this->service->rates(now());
        self::assertIsArray($result);
        self::assertNotEmpty($result);
        foreach ($result as $item) {
            self::assertIsArray($item);
            self::assertNotEmpty($item);
            self::assertArrayHasKey('code',$item);
            self::assertIsString($item['code']);
            self::assertEquals(3,strlen($item['code']));
            self::assertArrayHasKey('name',$item);
            self::assertIsString($item['name']);
            self::assertArrayHasKey('rate',$item);
            self::assertIsFloat($item['rate']);
        }
    }
    public function testRatesCache()
    {
        $this->service->rates(now());
        self::assertNotNull(\Cache::get('cbr.rates.'.now()->format('Y-m-d')));
    }

    public function testUnavailableCurrencyRate()
    {
        $this->expectException(CurrencyServiceException::class);
        $this->expectDeprecationMessage('Unavailable currency');
        $this->service->currencyRate('ERR',now());
    }

    /**
     * @dataProvider currencyProvider
     * @return void
     */
    public function testCurrencyRate($currency)
    {
        $result = $this->service->currencyRate($currency,now());
        self::assertIsFloat($result);
        self::assertGreaterThan(0,$result);
    }

    public function testUnavailablePair()
    {
        $this->expectException(CurrencyServiceException::class);
        $this->expectDeprecationMessage('Unavailable currency');
        $this->service->pairRate('RUR','ERR',now());
    }
    /**
     * @dataProvider currencyProvider
     * @return void
     */
    public function testPair($currency)
    {
        $result = $this->service->pairRate('RUR',$currency,now());
        self::assertIsFloat($result);
        self::assertGreaterThan(0,$result);
    }

    public function testUnavailableSamePair()
    {
        $this->expectException(CurrencyServiceException::class);
        $this->expectDeprecationMessage('Unavailable currency');
        $this->service->pairRate('ERR','ERR',now());
    }

    /**
     * @dataProvider currencyProvider
     * @return void
     */
    public function testSamePair($currency)
    {
        $result = $this->service->pairRate($currency,$currency,now());
        self::assertEquals(1.0, $result);
    }

    /**
     * @dataProvider currencyPairProvider
     * @return void
     */
    public function testCrossPair($base, $to)
    {
        $result = $this->service->pairRate($base,$to,now());
        self::assertIsFloat($result);
        self::assertGreaterThan(0,$result);
    }

    /**
     * @dataProvider currencyProvider
     * @return void
     */
    public function testInversePair($currency)
    {
        $rur_usd = $this->service->pairRate('RUR',$currency,now());
        $usd_rur = $this->service->pairRate($currency,'RUR',now());
        self::assertEquals($rur_usd,1/$usd_rur);
    }

    /**
     * @dataProvider currencyPairProvider
     * @return void
     */
    public function testInverseCrossPair($base, $to)
    {
        $eur_usd = $this->service->pairRate($base,$to,now());
        $usd_eur = $this->service->pairRate($to,$base,now());
        self::assertEquals($eur_usd,1/$usd_eur);
    }

    public function currencyProvider(): array
    {
        return [
            ['EUR'],
            ['USD'],
            ['AUD'],
            ['HUF'],
            ['SEK']
        ];
    }

    public function currencyPairProvider(): array
    {
        return [
            ['EUR','USD'],
            ['USD','EUR'],
            ['USD','CHF'],
            ['KRW','EUR'],
            ['JPY','KRW'],
            ['USD','SGD'],
        ];
    }

}
