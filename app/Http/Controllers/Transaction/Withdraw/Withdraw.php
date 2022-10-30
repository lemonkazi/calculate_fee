<?php

namespace App\Http\Controllers\Transaction\Withdraw;

use App\Http\Controllers\Commission\Commission;
use App\Interfaces\CommissionInterface;
use App\Traits\CommissionTrait;
use App\Traits\TransactionTrait;

/**
 * Withdraw type transaction class.
 *
 * Handles TransactionItem instance and process it.
 */
class Withdraw implements CommissionInterface
{
    use TransactionTrait;
    use CommissionTrait;

    /**
     * Get commission for a withdraw transaction.
     *
     * Apply rules based on amount.
     *
     * @return float $amount commission amount
     */
    public function getCommission(): float
    {
        /*
         * Process for business clients.
         *
         * We don't need to process additional week calculation.
         */
        if ($this->transactionItem->clientType === 'business') {
            return Commission::calculate($this->transactionItem->amount, $this->commissionFee);
        }

        /*
         * Process for private clients.
         *
         * We've used a WeeklyWithdraw singleton class to store withdrawals
         * as object in memory and calculate commission.
         */
        $weeklyWithdraw = WeeklyWithdraw::getInstance();

        return $weeklyWithdraw->getCommission($this);
    }

    /**
     * Set commission fee percent for Withdraw transaction by client type.
     *
     * @return self current class instance
     */
    public function setCommissionFeeByClientType(): self
    {
        switch ($this->transactionItem->clientType) {
            case 'private':
                $this->setCommissionFee(0.3);
                break;

            case 'business':
                $this->setCommissionFee(0.5);
                break;

            default:
                break;
        }

        return $this;
    }
}
