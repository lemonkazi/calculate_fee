<?php

namespace Tests\Unit\Commission;

use App\Commission\Commission;
use PHPUnit\Framework\TestCase;

class CommissionTest extends TestCase
{
    /**
     * @param float $transactionAmount
     * @param float $commissionFee
     * @param int   $expectation
     *
     * @dataProvider dataProviderForCommissionCalculation
     */
    public function testCommissionCalculation(float $transactionAmount, float $commissionFee, float $expectation)
    {
        $this->assertEquals(
            $expectation,
            Commission::calculate($transactionAmount, $commissionFee)
        );
    }

    public function dataProviderForCommissionCalculation(): array
    {
        return [
            'Commission for 1000 and fee 0.3' => [1000, 0.3, 3],
            'Commission for 300 and fee 0.3' => [300, 0.3, 0.9],
        ];
    }
}