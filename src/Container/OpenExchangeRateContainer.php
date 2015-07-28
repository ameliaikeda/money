<?php namespace Amelia\Money\Container;

use Carbon\Carbon;

/**
 * A container used for holding rates from an API, etc
 *
 * @author Amelia Ikeda (amelia@dorks.io)
 * @license MIT
 * @link https://github.com/ameliaikeda/money
 */
class OpenExchangeRateContainer implements RateContainerInterface {

    /**
     * Code => rate array of rates
     *
     * @var array
     */
    protected $rates;

    /**
     * Which rate (= 1.000) is everything based on
     *
     * @var string
     */
    protected $base;

    /**
     * What date were the dates fetched/generated?
     *
     * @var \Carbon\Carbon
     */
    protected $date;

    /**
     * Parse an openexchangerates response
     *
     * @param \stdClass $json
     */
    public function __construct($json)
    {
        $this->rates = (array) $json->rates;
        $this->base = (string) $json->base;
        $this->date = Carbon::createFromTimestampUTC($json->timestamp);
    }

    /**
     * Get an array of rates as code => rate
     *
     * @return array
     */
    public function getRates()
    {
        return $this->rates;
    }

    /**
     * Get the base currency used by the rates (base == 1.0000)
     *
     * @return string
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * Get the date that rates were fetched
     *
     * @return \Carbon\Carbon
     */
    public function getDate()
    {
        return $this->date;
    }
}
