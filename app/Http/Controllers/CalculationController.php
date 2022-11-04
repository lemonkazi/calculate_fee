<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Helpers\Currency\CurrencyContainer;
use App\Helpers\Currency\Currency;
use App\Helpers\ExchangeRate;

class CalculationController extends Controller
{

    /**
     * create a new instance of the class
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
    }

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
        return self::exchange($fromCurrency, $toCurrency, $amount);
    }

    /**
     * Convert transaction amount to base currency before making any calculation.
     *
     * @param TransactionItem $transactionItem transaction item needs to convert
     *
     * @return float converted amount
     */
    public static function convertToBaseCurrency($transactionItem): float
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
    public static function convertToOwnCurrency($transactionItem, float $commission): float
    {
        $currency = new Currency();
        return self::convert($commission, $currency->getBaseCurrency(), $transactionItem->currency);
    }


    /**
     * Convert currency from one currency to another.
     *
     * @param float $amount amount needs to convert
     *
     * @return float converted amount
     */
    public static function exchange($fromCurrency, $toCurrency,float $amount): float
    {
        // No need to process if both currencies are same.
        if ($fromCurrency->getCurrency() === $toCurrency->getCurrency()) {
            return $amount;
        }

        $fromRate = ExchangeRate::get($fromCurrency->getCurrency());
        $toRate = ExchangeRate::get($toCurrency->getCurrency());

        return self::changeRate($amount, $fromRate, $toRate);
    }
}