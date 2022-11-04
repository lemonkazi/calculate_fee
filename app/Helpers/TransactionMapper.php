<?php

namespace App\Helpers;


/**
 * TransactionItem mapper class.
 *
 * Map the `$lineData` array to an object.
 */
class TransactionMapper
{
    /**
     * Transaction date.
     */
    public string $date;

    /**
     * User ID.
     */
    public int $userId;

    /**
     * Client type.
     *
     * @example, private | business
     */
    public string $clientType;

    /**
     * Transaction type.
     *
     * @example, deposit | withdraw
     */
    public string $transactionType;

    /**
     * Transaction amount.
     */
    public float $amount;

    /**
     * Transaction currency.
     */
    public string $currency;

    /**
     * Class constructor.
     *
     * @param array $lineData line array data
     */
    public function __construct(array $lineData)
    {
        $column = $this->getColumnMapper();

        // Set the attributes by mapping.
        $this->date = isset($lineData[$column['date']]) ? $lineData[$column['date']] : '';
        $this->userId = isset($lineData[$column['userId']]) ? (int) $lineData[$column['userId']] : 0;
        $this->clientType = isset($lineData[$column['clientType']]) ? $lineData[$column['clientType']] : '';
        $this->transactionType = isset($lineData[$column['transactionType']]) ? $lineData[$column['transactionType']] : '';
        $this->amount = isset($lineData[$column['amount']]) ? (float) $lineData[$column['amount']] : 0;
        $this->currency = isset($lineData[$column['currency']]) ? $lineData[$column['currency']] : '';
    }

    /**
     * Get column mappers after key wise indexing.
     *
     * @return array column mappers
     */
    public function getColumnMapper(): array
    {
        return [
            'date' => 0,
            'userId' => 1,
            'clientType' => 2,
            'transactionType' => 3,
            'amount' => 4,
            'currency' => 5,
        ];
    }
}
