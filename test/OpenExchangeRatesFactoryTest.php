<?php namespace Amelia\Money;

use PHPUnit_Framework_TestCase;

/**
 * Simple functional test for the primary factory
 *
 * @author Amelia Ikeda (amelia@dorks.io)
 * @license MIT
 * @link https://github.com/ameliaikeda/money
 */
class OpenExchangeRatesFactoryTest extends PHPUnit_Framework_TestCase {

    /**
     * Factory Instance
     *
     * @var \Amelia\Money\FactoryInterface
     */
    protected $factory;

    /**
     * Set up the factory before tests
     *
     * @return void
     */
    public function setUp()
    {
        $this->factory = OpenExchangeRatesFactory::create('');
    }

    /**
     * Regression test for factory methods
     *
     * @return void
     */
    public function testCreatesGivesValidFactory()
    {
        $this->assertInstanceOf('Amelia\Money\FactoryInterface', $this->factory);
    }

    /**
     * Regression test for factory methods
     *
     * @return void
     */
    public function testApiMethodGivesTheRightApi()
    {
        $this->assertInstanceOf('Amelia\Money\FactoryInterface', $this->factory);
        $api = $this->factory->api();
        $this->assertInstanceOf('Amelia\Money\Api\ApiRepositoryInterface', $api);
        $this->assertInstanceOf('Amelia\Money\Api\OpenExchangeRates',      $api);
    }
}
