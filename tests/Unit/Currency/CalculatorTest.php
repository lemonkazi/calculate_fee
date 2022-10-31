<?php

namespace Tests\Unit\Currency;

use App\Http\Controllers\Currency\Calculator;
use Tests\TestCase;
use Illuminate\Support\Facades\Config;

class CalculatorTest extends TestCase
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
     *
     * @dataProvider dataProviderForRateChange
     */
    public function testChangeRate(float $amount, float $fromRate, float $toRate, float $expectation): void
    {
        $changedRate = Calculator::changeRate($amount, $fromRate, $toRate);

        $this->assertEquals(
            $expectation,
            $changedRate,
        );
    }

    public function dataProviderForRateChange(): array
    {
        return [
            'Change amount 100 by rate 1:4.5' => [100, 1, 4.5, 450],
            'Change amount 1 by rate 1:4.5' => [1, 1, 4.5, 4.5],

            // Check division by zero case.
            'Change amount 100 by wrong rate 0:5' => [100, 0, 5, 0],
        ];
    }
}
