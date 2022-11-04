<?php

namespace Tests\Unit;

use App\Traits\CommissionTrait;
use Tests\TestCase;

class CommissionTest extends TestCase
{
    use CommissionTrait;
    /**
     * @param float $transactionAmount
     * @param float $commissionFee
     * @param int   $expectation
     *
     * @dataProvider setProvider
     */
    public function testCommission(float $transactionAmount, float $commissionFee, float $expectation)
    {

        $this->assertEquals(
            $expectation,
            $this->commissionFee($transactionAmount, $commissionFee)
        );
    }

    public function setProvider(): array
    {
        return [
            'Commission for 1000 and fee 0.03' => [1000, 0.03, 0.3],
            'Commission for 600 and fee 0.6' => [600, 0.6, 3.6],
        ];
    }
}