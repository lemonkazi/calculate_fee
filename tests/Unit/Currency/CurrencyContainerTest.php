<?php


namespace Tests\Unit\Currency;

use App\Currency\Currency;
use App\Currency\CurrencyContainer;
use Tests\TestCase;
use Illuminate\Support\Facades\Config;

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
        $currencyJpy->setCurrency('JPY')
            ->setDecimals(0);

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
        $this->container->remove('EUR')
            ->remove('USD')
            ->remove('JPY');
    }

    /**
     * @test
     */
    public function testGetCurrencyContainerData()
    {
        /*
        * Test For USD Currency with Zero (2) decimals
        */
        $currencyUsd = $this->container->get('USD')->setDecimals(2);

        // Test if currency is instantiated.
        $this->assertTrue($currencyUsd instanceof Currency);

        // Test if decimals get works.
        $this->assertEquals(
            2,
            $currencyUsd->getDecimals()
        );

        /*
        * Test For JPY Currency with Zero (0) decimals
        */
        $currencyJpy = $this->container->get('JPY')->setDecimals(0);

        // Test if decimals get works.
        $this->assertEquals(
            0,
            $currencyJpy->getDecimals()
        );
    }
}
