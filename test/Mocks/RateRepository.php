<?php namespace Amelia\Money\Mocks;

use Amelia\Money\Api\RateRepositoryInterface;
use Carbon\Carbon;

/**
 * Mock implementation for storing rates. Data is provided from files in data/
 *
 * @author Amelia Ikeda (amelia@dorks.io)
 * @license MIT
 * @link https://github.com/ameliaikeda/money
 */
class RateRepository implements RateRepositoryInterface {

    /**
     * Array of data used by a mock api
     *
     * @var array
     */
    protected $data;

    /**
     * Construct a new mock rate repository
     *
     * @param array $data
     */
    public function construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get a key-value array of rates as currency_code => rate
     *
     * @return array
     */
    public function getRates()
    {
        return $this->data["rates"];
    }

    /**
     * Get the date that these rates were set/distributed
     *
     * @return \Carbon\Carbon
     */
    public function getDate()
    {
        return new Carbon($this->data["timestamp"]);
    }

    /**
     * Get the base currency used by this rate repository
     *
     * @return string A three-letter currency code which has rate = 1
     */
    public function getBase()
    {
        return $this->data["base"];
    }
}
