<?php namespace Amelia\Money\Container;

use PHPUnit_Framework_TestCase;
use stdClass;

/**
 * Test that the openexchangerates container works as expected.
 *
 * @author Amelia Ikeda (amelia@dorks.io)
 * @license MIT
 * @link https://github.com/ameliaikeda/money
 */
class OpenExchangeRatesContainerTest extends PHPUnit_Framework_TestCase
{
    protected static $json;

    public static function setUpBeforeClass()
    {
        static::$json = new stdClass();
        static::$json->rates = ['icecream' => 1.345, 'waffles' => 1.234];
        static::$json->base = 'unicorns';
        static::$json->timestamp = '1438246822';
    }

    public function testCreatesCorrectly()
    {
        $container = new OpenExchangeRateContainer(static::$json);
        $this->assertInstanceOf('Amelia\Money\Container\RateContainerInterface', $container);
    }

    public function testGettingRates()
    {
        $container = new OpenExchangeRateContainer(static::$json);
        $this->assertEquals(['icecream' => 1.345, 'waffles' => 1.234], $container->getRates());
    }

    public function testGettingBase()
    {
        $container = new OpenExchangeRateContainer(static::$json);
        $this->assertEquals('unicorns', $container->getBase());
    }

    public function testGettingDate()
    {
        $container = new OpenExchangeRateContainer(static::$json);
        $date = $container->getDate();
        $this->assertInstanceOf('Carbon\Carbon', $date);
        $this->assertEquals('1438246822', $date->getTimestamp());
        $this->assertEquals('2015-07-30', $date->format('Y-m-d'));
    }
}
