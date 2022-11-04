<?php

namespace Tests\Unit;

use App\Http\Controllers\CalculationController;
use Tests\TestCase;

class CalculationTest extends TestCase
{

    
    /**
     * @param float $amount
     * @param float $fromRate
     * @param float $toRate
     * @param float   $expectation
     *
     * @dataProvider dataProvider
     */
    public function testChangeRate(float $amount, float $fromRate, float $toRate, float $expectation): void
    {
        $changedRate = CalculationController::changeRate($amount, $fromRate, $toRate);

        $this->assertEquals(
            $expectation,
            $changedRate,
        );
    }

    public function dataProvider(): array
    {
        return [
            'Change amount 1 by rate 1:8' => [1, 1, 8, 8],
            'Change amount 100 by wrong rate 0:10' => [100, 0, 10, 0],
            'Change amount 200 by rate 1:5' => [200, 1, 5, 1000],
            
        ];
    }
}
