<?php

namespace Amelia\Money;

use Amelia\Money\Api\OpenExchangeRates;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

/**
 * Service Provider for Laravel 5 (technically works with 4).
 *
 * @author Amelia Ikeda (amelia@dorks.io)
 * @license MIT
 * @link https://github.com/ameliaikeda/money
 * @link http://laravel.com/docs/master/providers
 */
class MoneyServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Amelia\Money\FactoryInterface', function (Application $app) {
            $config = $app['config']->get('services.money', []);
            $type = array_get($config, 'api', 'openexchangerates');

            switch ($type) {
                case 'openexchangerates':
                default:
                    return OpenExchangeRatesFactory::create($config);
            }
        });
    }
}
