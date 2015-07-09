<?php namespace Amelia\Money\Container;

/**
 * A container used for holding rates from an API, etc
 *
 * @author Amelia Ikeda (amelia@dorks.io)
 * @license MIT
 * @link https://github.com/ameliaikeda/money
 */
interface RateContainerInterface {

    /**
     * Get an array of rates as code => rate
     *
     * @return array
     */
    public function getRates();

    /**
     * Get the base currency used by the rates (base == 1.0000)
     *
     * @return string
     */
    public function getBase();

    /**
     * Get the date that rates were fetched
     *
     * @return \Carbon\Carbon
     */
    public function getDate();
}
