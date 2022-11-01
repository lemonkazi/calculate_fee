<?php

namespace Tests\Unit\Currency;

use App\Helpers\Currency\Currency;
use Tests\TestCase;
use Illuminate\Support\Facades\Config;

class CurrencyTest extends TestCase
{

     /**
     * Add a csv file before starting the processes.
     *
     * @return void
     */
    protected function setup(): void
    {
        parent::setUp();
       
    }

    /**
     * Delete the file after processing.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();
    }
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
