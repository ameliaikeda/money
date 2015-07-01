<?php namespace Amelia\Money;

use InvalidArgumentException;

/**
 * Converter for currencies, similar to money.js
 *
 * @author Amelia Ikeda (amelia@dorks.io)
 * @license MIT
 * @link https://github.com/openexchangerates/money.js
 * @link https://github.com/ameliaikeda/money
 */
class Converter implements ConverterInterface {

    /**
     * A code => value list of rates
     *
     * @var array
     */
    protected $rates;

    /**
     * The base currency used by the converter, aka whatever currency is "1"
     *
     * @var string
     */
    protected $base;

    /**
     * Create a new (immutable) Converter instance
     *
     * @param array $rates Rate data in the form ["GBP" => 1.23456, ...]
     * @param string $base The currency that $rates are relative to
     */
    public function __construct(array $rates, $base = "GBP") {
        $this->rates = array_change_key_case($rates, CASE_UPPER);
        $this->base = strtoupper($base);

        if ( ! in_array($this->base, array_keys($this->rates))) {
            $this->rates[$this->base] = 1;
        }
    }

    /**
     * Make a new copy of this converter with the base changed to be $base.
     * Converters are immutable
     *
     * @param string $base A three-letter currency code to become the new base (must be in rates)
     * @return static
     */
    public function base($base) {
        $base = strtoupper($base);
        $rates = $this->newRates($base);

        return new static($rates, $base);
    }

    /**
     * Return an array of this object's rates made relative to $base
     *
     * @param string $base
     * @return array The new rates relative to $base
     */
    public function newRates($base) {
        $rates = $this->rates;
        $base = (1 / $rates[strtoupper($base)]);

        foreach ($rates as $code => $rate) {
            $rates[$code] = $rate * $base;
        }

        return $rates;
    }

    /**
     * Convert an amount from one currency to another
     *
     * @param float $amount The value to convert
     * @param string $from A three letter currency code to convert from
     * @param string $to A three-letter currency code to convert to
     *
     * @return float
     */
    public function convert($amount, $from, $to) {
        // base_from to base_to:
        // from_to = base_to * (1 / base_from)
        $rate = $this->rates[strtoupper($to)] * (1 / $this->rates[strtoupper($from)]);

        return $amount * $rate;
    }

    /**
     * Get the code => rate pairs used by this object
     *
     * @return array This object's internal rates
     */
    public function getRates()
    {
        return $this->rates;
    }

    /**
     * Return this object's base currency code.
     *
     * @return string The three-letter currency code describing this object's base currency
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * A helper class to check if this converter instance is relative to $base
     *
     * @param string $base a case-insensitive three-letter currency code
     * @return bool
     */
    public function is($base)
    {
        return strtoupper($base) === strtoupper($this->base);
    }

    /**
     * Get a single rate, relative to this object's base
     *
     * @param string $rate The three-letter currency code for the rate requested
     * @return float
     */
    public function getRate($rate)
    {
        return $this->rates[strtoupper($rate)];
    }
}
