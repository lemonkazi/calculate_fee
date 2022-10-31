<?php

namespace App\Commission;

/**
 * Commission calculation class.
 *
 * related to commission calculation.
 */
class Commission
{
    /**
     * Get commission from transaction.
     *
     * @param float $transactionAmount transaction amount
     * @param float $commissionFee     commission fee
     *
     * @return float commission amount
     */
    public static function calculate(float $transactionAmount, float $commissionFee): float
    {
        if ($commissionFee === 0) {
            return 0;
        }

        return ($transactionAmount * $commissionFee) / 100;
    }
}
