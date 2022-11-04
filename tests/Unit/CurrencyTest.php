<?php

namespace Tests\Unit;

use App\Helpers\Currency\Currency;
use Tests\TestCase;

class CurrencyTest extends TestCase
{

   
    /**
     * Test Set Curency
     *
     * @param string $currencyType
     *
     * @dataProvider setCurrencyProvider
     */
    public function testSetCurrency($currencyType, $expected)
    {
        $currency = new Currency();
        $currency->setCurrency($currencyType);

        $this->assertEquals(
            $expected,
            $currency->getCurrency()
        );
    }


    public function setCurrencyProvider()
    {

        return [
            ['USD', 'USD'],
            ['EUR', 'EUR']
        ];
    }
}
