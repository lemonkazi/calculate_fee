<?php

namespace Tests\Unit;

use App\Helpers\Commission;
use Tests\TestCase;

class CommissionTest extends TestCase
{
    /**
     * @param float $transactionAmount
     * @param float $commissionFee
     * @param int   $expectation
     *
     * @dataProvider setCommissionProvider
     */
    public function testCommission(float $transactionAmount, float $commissionFee, float $expectation)
    {
        $this->assertEquals(
            $expectation,
            Commission::commissionFee($transactionAmount, $commissionFee)
        );
    }

    public function setCommissionProvider(): array
    {
        return [
            'Commission for 1000 and fee 0.3' => [1000, 0.3, 3],
            'Commission for 300 and fee 0.3' => [300, 0.3, 0.9],
        ];
    }
}