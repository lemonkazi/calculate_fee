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

        $currencyUsd = new Currency();
        $currencyUsd->setCurrency('USD');

        $currencyJpy = new Currency();
        $currencyJpy->setCurrency('JPY');

        $this->container->add($baseCurrency)
            ->add($currencyUsd)
            ->add($currencyJpy);
    }

    /**
     * Remove data from currencies container after running test suits.
     */
    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function testGetCurrencyContainerData()
    {
        /*
        * Test For USD Currency with Zero (2) fractions
        */
        $currencyUsd = $this->container->get('USD');

        // Test if currency is instantiated.
        $this->assertTrue($currencyUsd instanceof Currency);

        // Test if fractions get works.
        $this->assertEquals(
            2,
            $currencyUsd->getFractions()
        );

        /*
        * Test For JPY Currency with Zero (0) fractions
        */
        $currencyJpy = $this->container->get('JPY');

        // Test if fractions get works.
        $this->assertEquals(
            0,
            $currencyJpy->getFractions()
        );
    }
}
