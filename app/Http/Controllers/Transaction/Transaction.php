<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Currency\Calculator;
use App\Interfaces\TransactionsInterface;
use App\Traits\MoneyFormatTrait;
use App\Http\Controllers\Transaction\Deposit\Deposit;
use App\Http\Controllers\Transaction\Withdraw\Withdraw;
use Exception;
use Throwable;

/**
 * Transaction Processor class.
 *
 * Handles TransactionItems[] and process it.
 */
class Transaction implements TransactionsInterface
{
    use MoneyFormatTrait;

    /**
     * Transaction responses.
     */
    public array $responses;

    /**
     * TransactionItems instance.
     */
    public $transactionItems;

    /**
     * Class constructor.
     */
    public function __construct($transactionItems)
    {
        $this->responses = [];
        $this->transactionItems = $transactionItems;
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
     * allProcesstransactions.
     *
     * It will handle a list of transactions.
     *
     * @return void processes the transaction items
     *
     * @throws Exception
     */
    public function allProcess(): void
    {
        try {
            $transactions = $this->transactionItems;

            foreach ($transactions as $transaction) {

                // Convert transaction amount to base currency
                $transaction->amount = Calculator::convertToBaseCurrency($transaction);

                // Process single transaction and get the commission.
                $commission = $this->process($transaction);


                // Revert back the currency to it's own currency.
                $commission = Calculator::convertCommissionAmountToOwnCurrency($transaction, $commission);
                // print_r($commission);
                // exit();
                // Add in our responses[] list for next processing
                $this->responses[] = $this->formatAmount($commission, $transaction->currency);
            }
        } catch (Exception $e) {
            throw new Exception('Something went wrong calculating. Error:' . $e->getMessage(), 400);
        }
    }
}
