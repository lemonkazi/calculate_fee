<?php

namespace Tests\Unit\Currency;

use App\Http\Controllers\Currency\Calculator;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
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
