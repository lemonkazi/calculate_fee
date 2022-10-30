<?php

namespace App\Http\Controllers\Currency;

use App\Http\Controllers\Controller;

/**
 * Currency calculator class.
 *
 * It'll handle all the calculation related to currencies.
 */
class Calculator
{
    /**
     * Change amount's rate from one currency to another currency.
     *
     * @param float $amount   amount needs to be converted
     * @param float $fromRate from rate value
     * @param float $toRate   to rate value
     *
     * @return float changed rate value
     */
    public static function changeRate(float $amount, float $fromRate, float $toRate): float
    {
        // Division by zero
        if (empty($fromRate) || empty($toRate)) {
            return 0;
        }

        return ($toRate / $fromRate) * $amount;
    }

    /**
     * Currency Exchange from one currency to another.
     *
     * @param float  $amount           amount needs to be converted
     * @param string $fromCurrencyName from currency name
     * @param string $toCurrencyName   to currency name
     *
     * @return float converted amount
     */
    public static function convert(float $amount, string $fromCurrencyName, string $toCurrencyName): float
    {
        $currencyData = CurrencyContainer::getInstance();
        $fromCurrency = $currencyData->get($fromCurrencyName);
        $toCurrency = $currencyData->get($toCurrencyName);
        $exchange = new Exchange($fromCurrency, $toCurrency);

        return $exchange->convert($amount);
    }

    /**
     * Convert transaction amount to base currency before making any calculation.
     *
     * @param TransactionItem $transactionItem transaction item needs to convert
     *
     * @return float converted amount
     */
    public static function convertTransactionAmountToBaseCurrency($transactionItem): float
    {
        $currency = new Currency();
        return self::convert($transactionItem->amount, $transactionItem->currency, $currency->getBaseCurrency());
    }

    /**
     * Revert back commission amount to transaction's own currency.
     *
     * @param TransactionItem $transactionItem transaction item instance
     * @param float           $commission      commission value needs to convert
     *
     * @return float converted commission amount
     */
    public static function convertCommissionAmountToOwnCurrency($transactionItem, float $commission): float
    {
        $currency = new Currency();
        return self::convert($commission, $currency->getBaseCurrency(), $transactionItem->currency);
    }
}
