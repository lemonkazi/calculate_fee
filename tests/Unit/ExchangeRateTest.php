<?php

namespace Tests\Unit;

use App\Helpers\ExchangeRate;
use Tests\TestCase;

class ExchangeRateTest extends TestCase
{

    
    /**
     * @param string $currency
     * @param mixed   $expected
     *
     * @dataProvider dataProvider
     */
    public function testGetExchange($currency,$expected)
    {
        $this->assertEquals(
            $expected,
            ExchangeRate::get($currency)
        );
    }

    public function dataProvider(): array
    {
        return [
            'Currency exchange rate for USD' => ['EUR', 1],
            'Currency exchange rate for JPY' => ['JPY', 130.869977],
        ];
    }
}
