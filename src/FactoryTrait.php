<?php namespace Amelia\Money;

use Carbon\Carbon;

/**
 * A trait that adds the ability to add boilerplate to a factory
 *
 * @author Amelia Ikeda (amelia@dorks.io)
 * @license MIT
 * @link https://github.com/ameliaikeda/money
 */
trait FactoryTrait
{
    /**
     * Converter instance used for initialising everything.
     *
     * @var \Amelia\Money\ConverterInterface
     */
    protected $converter;

    /**
     * Exchange rates API adapter.
     *
     * @var \Amelia\Money\Api\ApiInterface
     */
    protected $api;

    /**
     * Date used for fetching rates. If null, use latest.
     *
     * @var \Carbon\Carbon|null
     */
    protected $date;

    /**
     * Get the API instance used to populate rate data.
     *
     * @return \Amelia\Money\Api\Adapter\AdapterInterface
     */
    public function api()
    {
        return $this->api;
    }

    /**
     * Sets the date used by this class to fetch rates for.
     * Precision is entirely up to the implementation when providing a date.
     *
     * @param \Carbon\Carbon $date
     * @return $this
     */
    public function date(Carbon $date)
    {
        $this->latest();
        $this->date = $date;

        return $this;
    }

    /**
     * Get the current internal date, or null
     *
     * @return \Carbon\Carbon|null
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Revert back to using the latest rates data
     *
     * @return $this
     */
    public function latest()
    {
        $this->date = null;
        $this->converter = null;

        return $this;
    }
}
