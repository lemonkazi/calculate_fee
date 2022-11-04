<?php

namespace App\Interfaces;

/**
 * Interface Commission.
 */
interface CommissionInterface
{
    /**
     * Get the commission amount.
     */
    public function getCommission(): float;
}
