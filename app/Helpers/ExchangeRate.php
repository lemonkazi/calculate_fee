<?php


namespace App\Helpers;

use Illuminate\Support\Facades\Config;
/**
 * Currency Exchange data class.
 */
class ExchangeRate
{

    /**
     * Get Currency rates.
     *
     * @return array all currency lists as array
     */
    public static function all(): array
    {
        try{
            $ch = curl_init();
            $options = array(
                CURLOPT_URL => Config::get('global.EXCHANGE_RATES_URL'),
                CURLOPT_HEADER => false,
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_TIMEOUT => 05
            );
            curl_setopt_array($ch, $options);
            $resource = curl_exec($ch);
            curl_close($ch);
        } catch (\Exception $e) {
            return Config::get('global.exchange_rate');
        }
        
        if ($resource && !empty($resource)) {
            $resource = json_decode($resource, 1);           
        } else {
            return Config::get('global.exchange_rate');
        }
        return $resource['rates'];
    }

    /**
     * Get rate of a currency.
     *
     * @param string Get rate value of that currency
     *
     * @return float rate for given currency
     */
    public static function get(string $name): float
    {
        $rates = self::all();
        return isset($rates[$name]) ? floatval($rates[$name]) : 0;
    }
}
