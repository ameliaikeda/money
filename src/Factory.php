<?php namespace Amelia\Money;

use Amelia\Money\Api\ApiRepositoryInterface;
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
class Factory implements FactoryInterface {

    /**
     * Version used for User-Agent strings
     */
    const VERSION = "1.0.0";

    /**
     * Converter instance used for initialising everything
     *
     * @var \Amelia\Money\ConverterInterface
     */
    protected $converter;

    /**
     * Exchange rates API adapter
     *
     * @var \Amelia\Money\Api\ApiRepositoryInterface
     */
    protected $api;

    /**
     * Date used for fetching rates. If null, use latest.
     *
     * @var \Carbon\Carbon|null
     */
    protected $date;

    /**
     * @param \Amelia\Money\Api\ApiRepositoryInterface $api
     */
    public function __construct(ApiRepositoryInterface $api)
    {
        $this->api = $api;
    }

    /**
     * Get the API instance used to populate rate data
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

    /**
     *
     */
    protected function create()
    {
        if ($this->date) {
            $container = $this->api->getHistorical($this->date);
        } else {
            $container = $this->api->getLatest();
        }

        $this->converter = new Converter($container->getRates(), $container->getBase());
    }

    /**
     * Dynamically call the internal converter
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, array $arguments)
    {
        if ( ! $this->converter) {
            $this->create();
        }

        return call_user_func_array([$this->converter, $name], $arguments);
    }
}
