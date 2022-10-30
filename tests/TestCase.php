<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Config;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Add a csv file before starting the processes.
     *
     * @return void
     */
    protected function setup(): void
    {
        parent::setUp();
        Config::set('global.BASE_CURRENCY', env('BASE_CURRENCY', 'EUR'));
        Config::set('global.DEPOSIT_COMMISSION', env('DEPOSIT_COMMISSION', 0.03));
        Config::get('global.WEEKLY_FREE_LIMIT', env('WEEKLY_FREE_LIMIT', 1000));
        Config::get('global.WEEKLY_LIMIT', env('WEEKLY_LIMIT', 3));
        Config::set('global.exchange_rate', $this->exchangeRate());
        Config::get('global.WITHDRAW_PRIVATE_COMMUSSION', env('WITHDRAW_PRIVATE_COMMUSSION', 0.3));
        Config::get('global.WITHDRAW_BUSINESS_COMMUSSION', env('WITHDRAW_BUSINESS_COMMUSSION', 0.5));
        
        
    }

    /**
     * Delete the file after processing.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();
    }


     /**
     * Get test data.
     *
     * @return array Test datasets for the CSV file
     */
    public function exchangeRate(): array
    {
        return  [
            'AED' => 4.147043,
            'AFN' => 118.466773,
            'ALL' => 120.73174,
            'AMD' => 545.483468,
            'ANG' => 2.035477,
            'AOA' => 623.962579,
            'ARS' => 116.396925,
            'AUD' => 1.57676,
            'AWG' => 2.032821,
            'AZN' => 1.895163,
            'BAM' => 1.951459,
            'BBD' => 2.280333,
            'BDT' => 96.872638,
            'BGN' => 1.952581,
            'BHD' => 0.425669,
            'BIF' => 2253.093736,
            'BMD' => 1.129031,
            'BND' => 1.530499,
            'BOB' => 7.798334,
            'BRL' => 6.445617,
            'BSD' => 1.129391,
            'BTC' => 2.6156179e-5,
            'BTN' => 83.913403,
            'BWP' => 13.318607,
            'BYN' => 2.918863,
            'BYR' => 22129.014412,
            'BZD' => 2.276542,
            'CAD' => 1.445555,
            'CDF' => 2263.707335,
            'CHF' => 1.037789,
            'CLF' => 0.03431,
            'CLP' => 946.71533,
            'CNY' => 7.198254,
            'COP' => 4548.494719,
            'CRC' => 725.029021,
            'CUC' => 1.129031,
            'CUP' => 29.919331,
            'CVE' => 110.017623,
            'CZK' => 24.656241,
            'DJF' => 201.063247,
            'DKK' => 7.438803,
            'DOP' => 64.669308,
            'DZD' => 157.117122,
            'EGP' => 17.742178,
            'ERN' => 16.935558,
            'ETB' => 56.010148,
            'EUR' => 1,
            'FJD' => 2.399167,
            'FKP' => 0.851718,
            'GBP' => 0.835342,
            'GEL' => 3.494348,
            'GGP' => 0.851718,
            'GHS' => 6.97385,
            'GIP' => 0.851718,
            'GMD' => 59.609858,
            'GNF' => 10416.455146,
            'GTQ' => 8.718696,
            'GYD' => 236.28081,
            'HKD' => 8.806501,
            'HNL' => 27.726393,
            'HRK' => 7.522063,
            'HTG' => 115.219987,
            'HUF' => 363.074072,
            'IDR' => 16256.0756,
            'ILS' => 3.521099,
            'IMP' => 0.851718,
            'INR' => 84.06711,
            'IQD' => 1648.197205,
            'IRR' => 47701.574046,
            'ISK' => 146.819264,
            'JEP' => 0.851718,
            'JMD' => 173.825768,
            'JOD' => 0.800527,
            'JPY' => 130.869977,
            'KES' => 127.80595,
            'KGS' => 95.749402,
            'KHR' => 4602.273972,
            'KMF' => 490.335222,
            'KPW' => 1016.128125,
            'KRW' => 1357.965315,
            'KWD' => 0.341826,
            'KYD' => 0.941205,
            'KZT' => 491.795077,
            'LAK' => 12682.938295,
            'LBP' => 1707.874932,
            'LKR' => 228.134954,
            'LRD' => 164.950073,
            'LSL' => 17.929034,
            'LTL' => 3.333736,
            'LVL' => 0.682939,
            'LYD' => 5.197651,
            'MAD' => 10.455783,
            'MDL' => 20.15945,
            'MGA' => 4487.678598,
            'MKD' => 61.477311,
            'MMK' => 2008.074357,
            'MNT' => 3227.205877,
            'MOP' => 9.066072,
            'MRO' => 403.063997,
            'MUR' => 49.508135,
            'MVR' => 17.443978,
            'MWK' => 922.043265,
            'MXN' => 23.403578,
            'MYR' => 4.756044,
            'MZN' => 72.066134,
            'NAD' => 17.934686,
            'NGN' => 466.062578,
            'NIO' => 39.986153,
            'NOK' => 10.054905,
            'NPR' => 134.270657,
            'NZD' => 1.675177,
            'OMR' => 0.43408,
            'PAB' => 1.129371,
            'PEN' => 4.47123,
            'PGK' => 4.016376,
            'PHP' => 57.778153,
            'PKR' => 199.649788,
            'PLN' => 4.581315,
            'PYG' => 7790.689469,
            'QAR' => 4.110825,
            'RON' => 4.946966,
            'RSD' => 117.605521,
            'RUB' => 86.49171,
            'RWF' => 1171.766392,
            'SAR' => 4.240189,
            'SBD' => 9.123559,
            'SCR' => 15.83372,
            'SDG' => 493.951724,
            'SEK' => 10.33977,
            'SGD' => 1.53581,
            'SHP' => 1.555125,
            'SLL' => 12724.18312,
            'SOS' => 661.611904,
            'SRD' => 24.094088,
            'STD' => 23368.669389,
            'SVC' => 9.881999,
            'SYP' => 2836.683113,
            'SZL' => 17.9123,
            'THB' => 37.74317,
            'TJS' => 12.760798,
            'TMT' => 3.95161,
            'TND' => 3.246528,
            'TOP' => 2.571034,
            'TRY' => 15.612274,
            'TTD' => 7.678802,
            'TWD' => 31.218506,
            'TZS' => 2605.804869,
            'UAH' => 31.018778,
            'UGX' => 3998.045715,
            'USD' => 1.129031,
            'UYU' => 50.399435,
            'UZS' => 12218.550541,
            'VEF' => 241421024074.42,
            'VND' => 25691.672829,
            'VUV' => 127.865795,
            'WST' => 2.935675,
            'XAF' => 654.502727,
            'XAG' => 0.050046,
            'XAU' => 0.000626,
            'XCD' => 3.051263,
            'XDR' => 0.808703,
            'XOF' => 654.502727,
            'XPF' => 119.169197,
            'YER' => 282.540438,
            'ZAR' => 18.00901,
            'ZMK' => 10162.625635,
            'ZMW' => 18.934429,
            'ZWL' => 363.547633,
        ];
    }
}
