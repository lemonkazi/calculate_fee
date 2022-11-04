<?php


namespace Tests\Unit;

use App\Helpers\Currency\Currency;
use App\Helpers\Currency\CurrencyContainer;
use Tests\TestCase;

class CurrencyContainerTest extends TestCase
{
    /**
     * CurrencyContainer instance.
     *
     */
    private $container;

    /**
     * Add the currencies to the Currency Container.
     */
    protected function setup(): void
    {
        parent::setUp();
        $this->container = CurrencyContainer::getInstance();

        $baseCurrency = new Currency();
        $baseCurrency->setCurrency('EUR');

        $currincies = config('global.currency');
        foreach ($currincies as $value) {
            $currencySet = new Currency();
            $currencySet->setCurrency($value);
            $this->container->add($currencySet);
        }
    }

    /**
     * Remove data from currencies container after running test suits.
     */
    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @param string $currency
     * @param int   $expected
     *
     * @dataProvider dataProvider
     */
    public function testGetCurrencyContainerData($currency,$expected)
    {
        
        $currency = $this->container->get($currency);

        // Test if fractions get works.
        $this->assertEquals(
            $expected,
            $currency->getFractions()
        );
    }

    public function dataProvider(): array
    {
        return [
            'Currency fraction for USD' => ['USD', 2],
            'Currency fraction for JPY' => ['JPY', 0],
        ];
    }
}
