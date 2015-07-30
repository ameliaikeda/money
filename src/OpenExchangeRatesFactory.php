<?php namespace Amelia\Money;

use Amelia\Money\Api\Adapter\GuzzleHttpAdapter;
use Amelia\Money\Api\OpenExchangeRates;
use GuzzleHttp\Client;

/**
 * Slight hack for ease-of-use for the moment
 *
 * @author Amelia Ikeda (amelia@dorks.io)
 * @license MIT
 * @link https://github.com/ameliaikeda/money
 */
class OpenExchangeRatesFactory {

    /**
     * Create a new Factory instance set up for OpenExchangeRates
     *
     * @param string $key The App ID associated with an account on OpenExchangeRates
     * @return \Amelia\Money\FactoryInterface
     */
    public static function create($key)
    {
        $client = new Client([
            "base_uri" => "https://openexchangerates.org/api",
            "query"    => ["app_id" => $key],
            "headers"  => ["User-Agent" => "amelia/money (https://github.com/ameliaikeda/money) v" . Factory::VERSION],
        ]);

        return new Factory(new OpenExchangeRates(new GuzzleHttpAdapter($client)));
    }
}
