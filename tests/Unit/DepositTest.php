<?php

namespace Tests\Unit;

use App\Helpers\TransactionMapper;
use App\Helpers\Transaction\Deposit\Deposit;
use Tests\TestCase;

class DepositTest extends TestCase
{

    
    /**
     * @param string $lineData transaction item line data
     * @param string $expected expected commission
     *
     * @dataProvider dataProvider
     */
    public function testGetDepositCommission(string $lineData, float $expected)
    {
        $transactionItemData = explode(',', $lineData);
        $transactionItem = new TransactionMapper($transactionItemData);

        $deposit = new Deposit($transactionItem);
        $commission = $deposit->setDefaultCommissionFee()->getCommission();

        $this->assertEquals($expected, $commission);
    }

    public function dataProvider(): array
    {
        // Deposit commission fee  0.03%
        return [
            'Get deposit commission for private client of 500.00 EUR' => ['2016-01-10,2,private,deposit,500.00,EUR', 0.15], // 0.03 * 500 = 0.15
            'Get deposit commission for business client of 10000.00 EUR' => ['2016-01-10,2,business,deposit,10000.00,EUR', 3.0], // 0.03 * 10000 = 3.0
        ];
    }
}
