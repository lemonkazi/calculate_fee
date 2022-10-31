<?php

namespace App\Traits;

use App\Currency\CurrencyContainer;
use App\Currency\Currency;

trait MoneyFormatTrait
{
    /**
     * Format any amount to it's decimals with rounding.
     *
     * It's wrapper of number_format() but calculates decimals from currency
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
        $decimals = $currency ? $currency->getDecimals() : 2;
        return number_format(round($amount, $decimals), $decimals, $decimal_separator, $thousands_separator);
    }
}
