<?php

namespace App\Traits;

use App\Helpers\Currency\CurrencyContainer;

trait MoneyFormatTrait
{
    /**
     * Format any amount to it's fractions with rounding.
     *
     * It's wrapper of number_format() but calculates fractions from currency
     *
     * @param float  $amount              amount needs to format
     * @param string $currencyName        currency name
     * @param string $decimal_separator   decimal separator
     * @param string $thousands_separator thousands separator
     *
     * @see number_format()
     *
     * @return string formatted amount
     */
    public function formatAmount(
        float $amount,
        string $currencyName = 'EUR',
        ?string $decimal_separator = '.',
        ?string $thousands_separator = ''
    ): string {
        $currencyData = CurrencyContainer::getInstance();
        $currency = $currencyData->get($currencyName);
        $fractions = $currency ? $currency->getFractions() : 2;
        return number_format(round($amount, $fractions), $fractions, $decimal_separator, $thousands_separator);
    }
}
