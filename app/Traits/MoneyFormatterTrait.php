<?php

namespace App\Traits;

use App\Http\Controllers\Currency\CurrencyContainer;
use App\Http\Controllers\Currency\Currency;

trait MoneyFormatterTrait
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
    public function format(
        float $amount,
        string $currencyName = 'EUR',
        ?string $decimal_separator = '.',
        ?string $thousands_separator = ''
    ): string {
        $currencyData = CurrencyContainer::getInstance();
        $currency = $currencyData->get($currencyName);
        $decimals = $currency ? $currency->getDecimals() : 2;

        /*
         * Rounded up amount value for currencies which has no decimal point.
         * Eg: JPY, VND, KRW these currencies has no decimal
         */
        if ($currency->getDecimals() === 0) {
            $amount = ceil($amount);
        }

        return number_format(round($amount, $decimals), $decimals, $decimal_separator, $thousands_separator);
    }
}
