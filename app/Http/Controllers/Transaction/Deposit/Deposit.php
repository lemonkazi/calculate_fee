<?php

namespace App\Http\Controllers\Transaction\Deposit;

use App\Http\Controllers\Commission\Commission;
use App\Interfaces\CommissionInterface;
use App\Traits\CommissionTrait;
use App\Traits\TransactionTrait;
use Illuminate\Support\Facades\Config;

/**
 * Deposit type transaction class.
 *
 * Handles instance and process it.
 */
class Deposit implements CommissionInterface
{
    use TransactionTrait;
    use CommissionTrait;

    /**
     * Get commission amount for Deposit transaction.
     *
     * @return float get calculated commission
     */
    public function getCommission(): float
    {
        return Commission::calculate($this->transactionItem->amount, $this->commissionFee);
    }

    /**
     * Set default commission fee for all type of deposit.
     *
     * @return self current class instance
     */
    public function setDefaultCommissionFee(): self
    {
        $commu = Config::get('global.DEPOSIT_COMMISSION');
        $this->setCommissionFee($commu);

        return $this;
    }
}
