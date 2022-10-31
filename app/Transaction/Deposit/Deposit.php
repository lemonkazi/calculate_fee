<?php

namespace App\Transaction\Deposit;

use App\Commission\Commission;
use App\Interfaces\CommissionInterface;
use App\Traits\CommissionTrait;
use Illuminate\Support\Facades\Config;

/**
 * Deposit type transaction class.
 *
 * Handles instance and process it.
 */
class Deposit implements CommissionInterface
{
    use CommissionTrait;

    /**
     * Transaction amount instance.
     */
    public $transactionItem;

    
    public function __construct($transactionItem)
    {
        $this->transactionItem = $transactionItem;
    }
    /**
     * Get commission amount for Deposit transaction.
     *
     * @return float get calculated commission
     */
    public function getCommission(): float
    {
        return Commission::commissionFee($this->transactionItem->amount, $this->commissionFee);
    }

    /**
     * Set default commission fee for all type of deposit.
     *
     * @return self current class instance
     */
    public function setDefaultCommissionFee(): self
    {
        $this->setCommissionFee(Config::get('global.DEPOSIT_COMMISSION'));

        return $this;
    }
}
