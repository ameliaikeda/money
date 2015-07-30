<?php namespace Amelia\Money;

use Amelia\Money\Api\Adapter\GuzzleHttpAdapter;
use Amelia\Money\Api\ApiInterface;
use Amelia\Money\Api\OpenExchangeRates;
use GuzzleHttp\Client;

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
class OpenExchangeRatesFactory implements FactoryInterface {

    use FactoryTrait;

    /**
     * Version used for User-Agent strings
     */
    const VERSION = "1.0.0";

    /**
     * @param \Amelia\Money\Api\ApiInterface $api
     */
    public function __construct(ApiInterface $api)
    {
        $this->api = $api;
    }

    /**
     * Reconfigure the converter/re-fetch rates.
     *
     * @return void
     */
    protected function reconfigure()
    {
        if ($this->date) {
            $container = $this->api->getHistorical($this->date);
        } else {
            $container = $this->api->getLatest();
        }

        $this->converter = new Converter($container->getRates(), $container->getBase());
    }

    /**
     * Create a new Factory instance set up for OpenExchangeRates
     *
     * @param array $options
     * @return \Amelia\Money\FactoryInterface
     */
    public static function create(array $options)
    {
        $client = new Client([
            "base_uri" => "https://openexchangerates.org/api",
            "query"    => ["app_id" => isset($options["key"]) ? $options["key"] : null],
            "headers"  => ["User-Agent" => "amelia/money (https://github.com/ameliaikeda/money) v" . static::VERSION],
        ]);
        $api = new OpenExchangeRates(new GuzzleHttpAdapter($client));

        return new static($api);
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
            $this->reconfigure();
        }

        return call_user_func_array([$this->converter, $name], $arguments);
    }
}
