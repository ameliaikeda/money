<?php namespace Amelia\Money;

use Carbon\Carbon;

/**
 * Primary factory used for dealing with exchange rate data.
 *
 * @author Amelia Ikeda (amelia@dorks.io)
 * @license MIT
 * @link https://github.com/ameliaikeda/money
 *
 * @method float              convert(float $amount, string $to, string $from)
 * @method ConverterInterface base(string $base)
 * @method array              newRates(string $base)
 * @method array              getRates()
 * @method float              getRate(string $rate)
 * @method string             getBase()
 * @method bool               is(string $base)
 */
interface FactoryInterface {

    /**
     * Get the API instance used to populate rate data
     *
     * @return \Amelia\Money\Api\ApiInterface
     */
    public function api();

    /**
     * Sets the date used by this class to fetch rates for.
     * Precision is entirely up to the implementation when providing a date.
     *
     * @param \Carbon\Carbon $date
     * @return $this
     */
    public function date(Carbon $date);

    /**
     * Revert back to using the latest rates data
     *
     * @return $this
     */
    public function latest();

    /**
     * Create a new Factory instance set up for OpenExchangeRates
     *
     * @param array $options
     * @return \Amelia\Money\FactoryInterface
     */
    public static function create(array $options);
}
