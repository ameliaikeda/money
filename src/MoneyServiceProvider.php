<?php namespace Amelia\Money;

use Amelia\Money\Api\Adapter\GuzzleHttpAdapter;
use Amelia\Money\Api\OpenExchangeRates;
use GuzzleHttp\Client;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

/**
 * Service Provider for Laravel 5 (technically works with 4)
 *
 * @author Amelia Ikeda (amelia@dorks.io)
 * @license MIT
 * @link https://github.com/ameliaikeda/money
 * @link http://laravel.com/docs/master/providers
 */
class MoneyServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // todo: not hardcode this, and have Factory be able to figure out what to do
        // todo: bind each part separately
        $this->app->bind('Amelia\Money\FactoryInterface', function (Application $app) {
            $auth = $app['config']->get("services.money.app_key");
            $client = new Client([
                "base_uri" => "https://openexchangerates.org/api",
                "query"    => ["app_id" => $auth],
                "headers"  => ['User-Agent' => 'amelia/money (https://github.com/ameliaikeda/money) v' . Factory::VERSION],
            ]);

            return new Factory(new OpenExchangeRates(new GuzzleHttpAdapter($client)));
        });
    }
}
