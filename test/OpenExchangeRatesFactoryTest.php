<?php namespace Amelia\Money;

use PHPUnit_Framework_TestCase;

/**
 * Simple functional test for the primary factory.
 *
 * @author Amelia Ikeda (amelia@dorks.io)
 * @license MIT
 * @link https://github.com/ameliaikeda/money
 */
class OpenExchangeRatesFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * Regression test for factory methods.
     *
     * @return void
     */
    public function testCreatesGivesValidFactory()
    {
        $factory = OpenExchangeRatesFactory::create(['key' => '']);
        $this->assertInstanceOf('Amelia\Money\FactoryInterface', $factory);
    }

    /**
     * Regression test for factory methods.
     *
     * @return void
     */
    public function testApiMethodGivesTheRightApi()
    {
        $factory = OpenExchangeRatesFactory::create(['key' => '']);
        $api = $factory->api();
        $this->assertInstanceOf('Amelia\Money\Api\ApiInterface', $api);
        $this->assertInstanceOf('Amelia\Money\Api\OpenExchangeRates', $api);
    }

    /**
     * Test that the methods attached to converters are callable here, too.
     *
     * @return void
     */
    public function testProxyMethods()
    {
        $factory = OpenExchangeRatesFactory::create(['key' => '']);
        $this->assertTrue(is_callable([$factory, 'is']), 'Factory::is');
        $this->assertTrue(is_callable([$factory, 'base']), 'Factory::base');
        $this->assertTrue(is_callable([$factory, 'newRates']), 'Factory::newRates');
        $this->assertTrue(is_callable([$factory, 'getRates']), 'Factory::getRates');
        $this->assertTrue(is_callable([$factory, 'getRate']), 'Factory::getRate');
        $this->assertTrue(is_callable([$factory, 'getBase']), 'Factory::getBase');
        $this->assertTrue(is_callable([$factory, 'convert']), 'Factory::convert');
    }
}
