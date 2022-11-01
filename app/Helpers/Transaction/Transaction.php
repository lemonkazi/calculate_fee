<?php

namespace App\Helpers\Transaction;

use App\Http\Controllers\CalculationController;
use App\Traits\MoneyFormatTrait;
use App\Helpers\Transaction\Deposit\Deposit;
use App\Helpers\Transaction\Withdraw\Withdraw;
use Exception;
use Throwable;

/**
 * Transaction Processor class.
 *
 * Handles Items[] and process it.
 */
class Transaction
{
    use MoneyFormatTrait;

    /**
     * Transaction responses.
     */
    public array $responses;

    /**
     * Items instance.
     */
    public $items;

    /**
     * constructor.
     */
    public function __construct($transactionItems)
    {
        $this->responses = [];
        $this->items = $transactionItems;
    }

    /**
     * Process single Transaction item to get the commission.
     *
     * @param TransactionItem $transactionItem transaction item instance
     *
     * @return float processed commission amount
     *
     * @throws Exception
     */
    public function process($transactionItem): float
    {
        $amount = 0;
        try {
            switch ($transactionItem->transactionType) {
                case 'deposit':
                    $deposit = new Deposit($transactionItem);
                    $amount = $deposit->setDefaultCommissionFee()->getCommission();
                    break;

                case 'withdraw':
                    $withdraw = new Withdraw($transactionItem);
                    $amount = $withdraw->setCommissionFeeByClientType()->getCommission();
                    break;

                default:
                    break;
            }

            return $amount;
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * allProcess .
     *
     * It will handle a list of transactions.
     *
     * @return void processes the transaction items
     *
     */
    public function allProcess(): void
    {
        try {
            $transactions = $this->items;
            foreach ($transactions as $transaction) {
                // Convert item amount to base currency
                $transaction->amount = CalculationController::convertToBaseCurrency($transaction);
                // Process single transaction and get the commission.
                $commission = $this->process($transaction);
                // Revert back own currency.
                $commission = CalculationController::convertToOwnCurrency($transaction, $commission);
                // Add in our responses[]
                $this->responses[] = $this->formatAmount($commission, $transaction->currency);
            }
        } catch (Exception $e) {
            throw new Exception('Something went wrong calculating. Error:' . $e->getMessage(), 400);
        }
    }
}
