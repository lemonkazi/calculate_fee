<?php

namespace Tests\Unit\Currency;

use App\Http\Controllers\Currency\ExchangeRate;
use PHPUnit\Framework\TestCase;

class ExchangeRateTest extends TestCase
{
    /**
     * @test
     */
    public function testGetConversion()
    {
        $this->assertEquals(
            1,
            ExchangeRate::get('EUR')
        );

        $this->assertEquals(
            130.869977,
            ExchangeRate::get('JPY')
        );
    }
}
