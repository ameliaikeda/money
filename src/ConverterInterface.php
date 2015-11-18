<?php

namespace Amelia\Money;

/**
 * Main Converter interface to rely on; Immutable.
 *
 * @author Amelia Ikeda (amelia@dorks.io)
 * @license MIT
 * @link https://github.com/openexchangerates/money.js
 * @link https://github.com/ameliaikeda/money
 */
interface ConverterInterface
{
    /**
     * Convert an amount from one currency to another.
     *
     * @param float $amount The value to convert
     * @param string $from A three letter currency code to convert from
     * @param string $to A three-letter currency code to convert to
     *
     * @return float
     */
    public function convert($amount, $from, $to);

    /**
     * Make a new copy of this converter with the base changed to be $base.
     * Converters are immutable.
     *
     * @param string $base A three-letter currency code to become the new base (must be in rates)
     * @return static
     */
    public function base($base);

    /**
     * Return an array of this object's rates made relative to $base.
     *
     * @param string $base
     * @return array The new rates relative to $base
     */
    public function newRates($base);

    /**
     * Get the code => rate pairs used by this object.
     *
     * @return array This object's internal rates
     */
    public function getRates();

    /**
     * Get a single rate, relative to this object's base.
     *
     * @param string $rate The three-letter currency code for the rate requested
     * @return float
     */
    public function getRate($rate);

    /**
     * Return this object's base currency code.
     *
     * @return string The three-letter currency code describing this object's base currency
     */
    public function getBase();

    /**
     * A helper class to check if this converter instance is relative to $base.
     *
     * @param string $base a case-insensitive three-letter currency code
     * @return bool
     */
    public function is($base);
}
