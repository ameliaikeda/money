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
     * Parse an openexchangerates response
     *
     * @param array|\ArrayObject $json
     */
    public function __construct($json)
    {
        $this->rates = $json['rates'];
        $this->base = $json['base'];
        $this->date = Carbon::parse($json['timestamp']);
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
