<?php

namespace App\Helpers;

/**
 * Commission calculation class.
 *
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
    public static function commissionFee(float $transactionAmount, float $commissionFee): float
    {
        if ($commissionFee === 0) {
            return 0;
        }
        return ($transactionAmount * $commissionFee) / 100;
    }
}