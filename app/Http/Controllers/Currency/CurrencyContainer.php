<?php

namespace App\Http\Controllers\Currency;

use App\Http\Controllers\Controller;

/**
 * Currency Container class.
 *
 * We use this just for storing our currencies in Memory.
 */
class CurrencyContainer
{


    //use SingletonTrait;

    /**
     * Get all currencies.
     *
     * @var array<Currency> Get the list of currencies data
     */
    public array $currencies;

    /**
     * Singleton Instance.
     *
     * @var self
     */
    private static $instance;
    /**
     * Get the singleton instance.
     *
     * @return $this
     */
    public static function getInstance(): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Add new currency to the container.
     *
     * @param Currency $currency Currency object
     *
     * @return self Current class instance
     */
    public function add(Currency $currency): self
    {
        if (empty($this->currencies)) {
            $this->currencies = [];
        }

        if (!isset($this->currencies[$currency->getCurrency()])) {
            $this->currencies[$currency->getCurrency()] = $currency;
        }

        return $this;
    }

    /**
     * Get a currency from the container.
     *
     * @param string $currencyName Passed currency
     *
     * @return Currency Currency
     *
     * @throws Exception
     */
    public function get(string $currencyName): Currency
    {
        $currencyName = trim($currencyName);

        if (empty($this->currencies[$currencyName])) {
            throw new \Exception('No currency found by this name - ' . $currencyName, 400);
        }

        return $this->currencies[$currencyName];
    }

    /**
     * Remove a currency from the container.
     *
     * @param string $currencyName currency name
     *
     * @return self container class instance
     */
    public function remove(string $currencyName): self
    {
        if (isset($this->currencies[$currencyName])) {
            unset($this->currencies[$currencyName]);
        }

        return $this;
    }
}
