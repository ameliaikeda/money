<?php namespace Amelia\Money\Api;

/**
 * General repository-pattern container object used for storing rates.
 * Implementations decide how to fetch data
 *
 * @author Amelia Ikeda (amelia@dorks.io)
 * @license MIT
 * @link https://github.com/ameliaikeda/money
 */
interface RateRepositoryInterface {

    /**
     * Get a key-value array of rates as currency_code => rate
     *
     * @return array
     */
    public function getRates();

    /**
     * Get the date that these rates were set/distributed
     *
     * @return \Carbon\Carbon
     */
    public function getDate();

    /**
     * Get the base currency used by this rate repository
     *
     * @return string A three-letter currency code which has rate = 1
     */
    public function getBase();
}
