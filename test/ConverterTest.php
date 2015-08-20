<?php namespace Amelia\Money;

use PHPUnit_Framework_TestCase;

/**
 * Unit tests for the converter.
 *
 * @author Amelia Ikeda (amelia@dorks.io)
 * @license MIT
 * @link https://github.com/ameliaikeda/money
 */
class ConverterTest extends PHPUnit_Framework_TestCase
{
    /**
     * Sample rates (provided by open exchange rates).
     *
     * @var array
     */
    protected $rates = [
        'AUD' => 1.293587,
        'CAD' => 1.233466,
        'CHF' => 0.930574,
        'CNY' => 6.190034,
        'CZK' => 24.20269,
        'DKK' => 6.618482,
        'EUR' => 0.886697,
        'GBP' => 0.641963,
        'HKD' => 7.752583,
        'ILS' => 3.833653,
        'INR' => 64.20142,
        'IRR' => 29062.5,
        'ISK' => 132.220001,
        'JPY' => 123.525799,
        'PHP' => 45.19099,
        'PLN' => 3.689478,
        'RSD' => 106.77658,
        'RUB' => 54.18877,
        'SEK' => 8.17644,
        'SGD' => 1.345834,
        'USD' => 1,
    ];

    /**
     * Base currency that $rates are relative to.
     *
     * @var string
     */
    protected $base = 'USD';

    /**
     * Test that we can create a converter and that it adheres to an interface.
     *
     * @return \Amelia\Money\ConverterInterface
     */
    public function testCreate()
    {
        $converter = new Converter($this->rates, $this->base);
        $this->assertInstanceOf('Amelia\\Money\\ConverterInterface', $converter);

        return $converter;
    }

    /**
     * Test that rates default to GBP when not provided, as changing is a BC break.
     *
     * @return void
     */
    public function testRatesDefaultToGbp()
    {
        $converter = new Converter($this->rates);
        $this->assertEquals('GBP', $converter->getBase());
    }

    /**
     * Test that setting a missing rate as the base currency will add itself as the base.
     *
     * @return void
     */
    public function testMissingRateAddsItself()
    {
        $converter = new Converter($this->rates, 'XAU');
        $this->assertEquals(1, $converter->getRate('XAU'));
    }

    /**
     * Test that rates can be fetched via getRate.
     *
     * @depends testCreate
     * @param \Amelia\Money\ConverterInterface $converter
     */
    public function testFetchingRates(ConverterInterface $converter)
    {
        $this->assertEquals(1, $converter->getRate('USD'), '1 USD = 1 USD', 0.000001);
        $this->assertEquals(0.641963, $converter->getRate('GBP'), '1 USD = 0.641963 GBP', 0.000001);
        $this->assertEquals(123.525799, $converter->getRate('JPY'), '1 USD = 123.525799 JPY', 0.000001);
    }

    /**
     * Test that rates returned are the same as those provided.
     *
     * @return void
     */
    public function testRatesReturn()
    {
        $converter = new Converter($this->rates);
        $this->assertSame($this->rates, $converter->getRates());
    }

    /**
     * Create a converter object with a GBP base for use in tests.
     *
     * @param \Amelia\Money\ConverterInterface $converter
     * @depends testCreate
     *
     * @return \Amelia\Money\ConverterInterface
     */
    public function testBaseGBP(ConverterInterface $converter)
    {
        $new = $converter->base('GBP');
        $this->assertInstanceOf('Amelia\\Money\\ConverterInterface', $new);
        $this->assertEquals('GBP', $new->getBase());

        return $new;
    }

    /**
     * Create a converter object with an AUD base for use in tests.
     *
     * @param \Amelia\Money\ConverterInterface $converter
     * @depends testCreate
     *
     * @return \Amelia\Money\ConverterInterface
     */
    public function testBaseAUD(ConverterInterface $converter)
    {
        $new = $converter->base('AUD');
        $this->assertInstanceOf('Amelia\\Money\\ConverterInterface', $new);
        $this->assertEquals('AUD', $new->getBase());

        return $new;
    }

    /**
     * Create a converter object with a SGD base for use in tests.
     *
     * @param \Amelia\Money\ConverterInterface $converter
     * @depends testCreate
     *
     * @return \Amelia\Money\ConverterInterface
     */
    public function testBaseSGD(ConverterInterface $converter)
    {
        $new = $converter->base('SGD');
        $this->assertInstanceOf('Amelia\\Money\\ConverterInterface', $new);
        $this->assertEquals('SGD', $new->getBase());

        return $new;
    }

    /**
     * Create a converter object with a EUR base for use in tests.
     *
     * @param \Amelia\Money\ConverterInterface $converter
     * @depends testCreate
     *
     * @return \Amelia\Money\ConverterInterface
     */
    public function testBaseEUR(ConverterInterface $converter)
    {
        $new = $converter->base('EUR');
        $this->assertInstanceOf('Amelia\\Money\\ConverterInterface', $new);
        $this->assertEquals('EUR', $new->getBase());

        return $new;
    }

    /**
     * Test that rates can be fetched via getRate.
     *
     * @depends testBaseGBP
     * @param \Amelia\Money\ConverterInterface $converter
     */
    public function testFetchingRatesFromAnotherBase(ConverterInterface $converter)
    {
        $this->assertEquals(1, $converter->getRate('GBP'), '1 GBP = 1 GBP', 0.000001);
        $this->assertEquals(1.557722, $converter->getRate('USD'), '1 GBP = 1.557722 USD', 0.000001);
        $this->assertEquals(192.418876, $converter->getRate('JPY'), '1 GBP = 192.418876 JPY', 0.000001);
    }

    /**
     * Data provider to simplify regression/math error tests.
     *
     * @return array
     */
    public function bases()
    {
        $converter = new Converter($this->rates, $this->base);

        return [
            [$converter],
            [$converter->base('USD')],
            [$converter->base('GBP')],
            [$converter->base('AUD')],
            [$converter->base('SGD')],
            [$converter->base('EUR')],
            [$converter->base('PHP')],
            [$converter->base('SEK')],
            [$converter->base('JPY')],
        ];
    }

    /**
     * Test converters are immutable.
     *
     * @param \Amelia\Money\ConverterInterface $converter
     * @depends testCreate
     * @return void
     */
    public function testBaseImmutable(ConverterInterface $converter)
    {
        $old = $converter;
        $new = $converter->base('EUR');

        $this->assertNotSame($old, $new);
        $this->assertSame($this->rates, $old->getRates());
    }

    /**
     * Test that switching the base also actually changes the rates
     * and that we dont just have a clone of some sort that didn't update.
     *
     * @param \Amelia\Money\ConverterInterface $new
     * @depends testBaseGBP
     *
     * @return void
     */
    public function testSwitchingBaseChangesRates(ConverterInterface $new)
    {
        $rates = $new->getRates();
        $base = $this->rates['GBP'];

        foreach ($this->rates as $code => $rate) {
            // new_rate = old_rate * (1 / new_base)
            $this->assertEquals($rates[$code], $rate * (1 / $base));
        }
    }

    /**
     * Test converting to operational rates in other countries.
     *
     * @dataProvider bases
     * @param \Amelia\Money\ConverterInterface $converter
     *
     * @return void
     */
    public function testConvertOperationalRates(ConverterInterface $converter)
    {
        // USD Rates
        $this->assertEquals(7.703556, $converter->convert(12, 'USD', 'GBP'), 'USD to GBP', 0.0001);
        $this->assertEquals(16.150008, $converter->convert(12, 'USD', 'SGD'), 'USD to SGD', 0.0001);
        $this->assertEquals(15.523044, $converter->convert(12, 'USD', 'AUD'), 'USD to AUD', 0.0001);
        $this->assertEquals(10.640364, $converter->convert(12, 'USD', 'EUR'), 'USD to EUR', 0.0001);

        // GBP rates
        $this->assertEquals(18.692666, $converter->convert(12, 'GBP', 'USD'), 'GBP to USD', 0.0001);
        $this->assertEquals(25.157225, $converter->convert(12, 'GBP', 'SGD'), 'GBP to SGD', 0.0001);
        $this->assertEquals(24.180589, $converter->convert(12, 'GBP', 'AUD'), 'GBP to AUD', 0.0001);
        $this->assertEquals(16.574730, $converter->convert(12, 'GBP', 'EUR'), 'GBP to EUR', 0.0001);

        // SGD rates
        $this->assertEquals(8.916404, $converter->convert(12, 'SGD', 'USD'), 'SGD to USD', 0.0001);
        $this->assertEquals(5.724001, $converter->convert(12, 'SGD', 'GBP'), 'SGD to GBP', 0.0001);
        $this->assertEquals(11.534144, $converter->convert(12, 'SGD', 'AUD'), 'SGD to AUD', 0.0001);
        $this->assertEquals(7.906148, $converter->convert(12, 'SGD', 'EUR'), 'SGD to EUR', 0.0001);

        // AUD rates
        $this->assertEquals(9.276531, $converter->convert(12, 'AUD', 'USD'), 'AUD to USD', 0.0001);
        $this->assertEquals(12.484670, $converter->convert(12, 'AUD', 'SGD'), 'AUD to SGD', 0.0001);
        $this->assertEquals(5.955189, $converter->convert(12, 'AUD', 'GBP'), 'AUD to GBP', 0.0001);
        $this->assertEquals(8.225472, $converter->convert(12, 'AUD', 'EUR'), 'AUD to EUR', 0.0001);

        // EUR rates
        $this->assertEquals(13.533371, $converter->convert(12, 'EUR', 'USD'), 'EUR to USD', 0.0001);
        $this->assertEquals(18.213671, $converter->convert(12, 'EUR', 'SGD'), 'EUR to SGD', 0.0001);
        $this->assertEquals(17.506593, $converter->convert(12, 'EUR', 'AUD'), 'EUR to AUD', 0.0001);
        $this->assertEquals(8.687923, $converter->convert(12, 'EUR', 'GBP'), 'EUR to GBP', 0.0001);
    }

    /**
     * Test that checking if a converter is relative to a base is case insensitive.
     *
     * @depends testCreate
     * @param \Amelia\Money\ConverterInterface $converter
     *
     * @return void
     */
    public function testCaseInsensitiveBases(ConverterInterface $converter)
    {
        $this->assertTrue($converter->is('usd'));
        $this->assertTrue($converter->is('USD'));
    }

    /**
     * Test that a converter can be created/used from case-insensitive rates.
     *
     * @return void
     */
    public function testCaseInsensitiveComparison()
    {
        $converter = new Converter(['EUR' => 0.886697, 'Gbp' => 0.641963, 'hkd' => 7.752583, 'usD' => 1], 'usd');
        $this->assertEquals(10.640364, $converter->convert(12, 'usd', 'EuR'), 'USD to EUR', 0.0001);
    }
}
