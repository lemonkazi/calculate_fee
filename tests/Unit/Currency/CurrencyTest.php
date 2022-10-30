<?php

namespace Tests\Unit\Currency;

use App\Http\Controllers\Currency\Currency;
use PHPUnit\Framework\TestCase;

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
        $currency->setCurrency($currencyType)
            ->setDecimals(4);

        $this->assertEquals(
            $expected,
            $currency->getCurrency()
        );

        $this->assertEquals(
            4,
            $currency->getDecimals()
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
