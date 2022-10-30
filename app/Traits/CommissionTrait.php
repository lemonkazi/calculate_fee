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
}
