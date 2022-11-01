<?php

namespace App\Helpers\Currency;

use Illuminate\Support\Facades\Config;

class Currency
{
    /**
     * Get Base currency name.
     *
     * @var string
     */
    public $BASE_CURRENCY;

    /**
     * Currency name.
     *
     * @var string currency name
     */
    private string $currency;

    /**
     * Currency fractions
     *
     * @var array
     */
    static protected $fractions; 

    public function __construct()
    {
        self::$fractions = Config::get('global.CURRENCY_FRACTION');
    }
    /**
     * Get the value of currency name.
     *
     * @return string currency name
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * Get the value of currency name.
     *
     * @return string currency name
     */
    public function getBaseCurrency(): string
    {
        try {
            return $this->BASE_CURRENCY = Config::get('global.BASE_CURRENCY');
        } catch (\Throwable $th) {
            return $this->BASE_CURRENCY = 'EUR';
        }
    }

    /**
     * Set the value of currency name.
     *
     * @param string $currency currency name
     *
     * @return self currency class instance
     */
    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * Get the value of decimals.
     *
     * @return int decimal point
     */
    public function getDecimals(): int
    {
        //return $this->decimals;
        $currency = strtoupper($this->currency);
        if (!isset(static::$fractions[$currency])) {
            throw new \Exception(sprintf('Unsupported currency: %s', $currency));
        }
        return static::$fractions[$currency];
    }

    /**
     * Set the value of decimals.
     *
     * @param int $decimal decimal point
     *
     * @return self currency class instance
     */
    public function setDecimals(int $decimals): self
    {
        $this->decimals = $decimals;
        return $this;
    }
}
