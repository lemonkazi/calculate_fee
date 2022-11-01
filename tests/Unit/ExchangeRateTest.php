<?php

namespace Tests\Unit;

use App\Helpers\ExchangeRate;
use Tests\TestCase;

class ExchangeRateTest extends TestCase
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
     * @test
     */
    public function testGetExchange()
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
