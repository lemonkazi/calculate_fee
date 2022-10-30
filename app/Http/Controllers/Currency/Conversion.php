<?php

namespace App\Http\Controllers\Currency;

use App\Http\Controllers\Controller;

/**
 * Currency Conversion.
 */
class Conversion
{
    /**
     * From currency.
     *
     * @var Currency from currency instance
     */
    public $from;

    /**
     * To currency.
     *
     * @var Currency to currency instance
     */
    public $to;

    /**
     * Class constructor.
     *
     * @param Currency $from From currency needs to convert
     * @param Currency $to   Converted to this currency
     */
    public function __construct($from,  $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * Convert currency from one currency to another.
     *
     * @param float $amount amount needs to convert
     *
     * @return float converted amount
     */
    public function convert(float $amount): float
    {
        // No need to process if both currencies are same.
        if ($this->from->getCurrency() === $this->to->getCurrency()) {
            return $amount;
        }

        $fromRate = ExchangeRate::get($this->from->getCurrency());
        $toRate = ExchangeRate::get($this->to->getCurrency());

        return Calculator::changeRate($amount, $fromRate, $toRate);
    }
}
