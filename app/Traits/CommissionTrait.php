<?php

namespace App\Traits;

/**
 * Trait commission trait.
 *
 * This will set and get default commission fee.
 */
trait CommissionTrait
{
    /**
     * Commission fee for transaction.
     *
     * @var float commission fee amount
     */
    public float $commissionFee = 0;

    /**
     * Set commission fee for transaction.
     *
     * @param float $amount commission fee amount
     *
     * @return self current class instance
     */
    public function setCommissionFee(float $amount): self
    {
        $this->commissionFee = $amount;

        return $this;
    }

    /**
     * Get commission from transaction.
     *
     * @param float $transactionAmount transaction amount
     * @param float $commissionFee     commission fee
     *
     * @return float commission amount
     */
    public function commissionFee(float $transactionAmount, float $commissionFee): float
    {
        if ($commissionFee === 0) {
            return 0;
        }
        return ($transactionAmount * $commissionFee) / 100;
    }
}
