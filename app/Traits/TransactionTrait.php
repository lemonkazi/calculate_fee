<?php

namespace App\Traits;

/**
 * Transaction Trait.
 *
 * Handles the transaction related classes like Withdraw, Deposit.
 */
trait TransactionTrait
{
    /**
     * Transaction amount instance.
     */
    public $transactionItem;

    /**
     * Trait constructor.
     */
    public function __construct($transactionItem)
    {
        $this->transactionItem = $transactionItem;
    }
}
