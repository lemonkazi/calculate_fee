<?php

namespace App\Interfaces;

/**
 * Interface Transactions.
 */
interface TransactionsInterface
{
    /**
     * Process single Transaction item.
     */
    public function process($transactionItem): float;

    /**
     * Process bulk Transaction items.
     */
    public function allProcess(): void;
}
