<?php

namespace Tests\Feature;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ApiMethodTest extends TestCase
{
    public function testRatesList()
    {
        $this->get(route('rates'))
            ->assertSuccessful()
            ->assertJsonStructure([
                '*' => [
                    'code',
                    'name',
                    'rate'
                ]
            ])
            ->assertJson(fn (AssertableJson $json) =>
            $json->whereType('0.name', 'string')
                ->whereType('0.code', 'string')
                ->whereType('0.rate', 'double')
            );
    }

    public function testRates()
    {
        $this->get(route('pair',['base'=>'RUR', 'to'=>'EUR']))
            ->assertSuccessful()
            ->assertJsonStructure([
                'rate',
                'change',
            ])
            ->assertJson(fn (AssertableJson $json) =>
            $json->whereType('rate', 'double')
                ->whereType('change', 'double')
            );
    }

    public function testUnavailbleRates()
    {
        $this->get(route('pair',['base'=>'ERR', 'to'=>'EUR']))
            ->assertStatus(422)
            ->assertJsonPath('message','Unavailable currency');
    }
}
