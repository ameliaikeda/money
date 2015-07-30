Money.php
---------

[![Build Status](https://travis-ci.org/ameliaikeda/money.svg?branch=master)](https://travis-ci.org/ameliaikeda/money)
[![Scrutinizer](https://scrutinizer-ci.com/g/ameliaikeda/money/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ameliaikeda/money/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/amelia/money/version)](https://packagist.org/packages/amelia/money)
[![MIT License](https://poser.pugx.org/amelia/money/license)](https://packagist.org/packages/amelia/money)

A PHP 5.4+ library for working with currency APIs and conversion.

Current Providers for data are:

* Open Exchange Rates (https://openexchangerates.org)

Planned:

* Her Majesty's Revenue & Customs (http://www.hmrc.gov.uk/softwaredevelopers/2015-exrates.htm)
* XE.com (https://xe.com)

Feel free to add more!

Usage
=====

To dive in with the OpenExchangeRates API implementation, go [sign up for an app id][oer-signup]. It's free!

## Laravel 5

Money ships with a Laravel 5 service provider.

Simply add `Amelia\Money\MoneyServiceProvider::class` to your providers array in `config/app.php`.

You can type-hint `Amelia\Money\FactoryInterface` in controllers (or more importantly, form requests), or use `app(FactoryInterface::class)` to fetch it from the IoC container.

```php
<?php

use Amelia\Money\FactoryInterface;

class FooController extends Controller {
    public function __construct(FactoryInterface $money) {
        $money->convert($amount = 130.01, $from = "USD", $to = "GBP");
        $money->getBase();
        $money->getRates();
        $newMoney = $money->base("GBP"); // switch the base currency
    }
}
```
Add the following configuration to the `config/services.php` array

```php
"money" => [
    "api" => env("MONEY_API_TYPE", "openexchangerates"),
    "key" => env("MONEY_API_KEY", null),
],
```

## Usage without a framework:

```php
<?php

use Amelia\Money\OpenExchangeRatesFactory;
$converter = OpenExchangeRatesFactory::create(["key" => "YOUR_APP_ID"]);

$converter->convert(140, "gbp", "nok"); // currency codes are case-insensitive.
var_dump($converter->getRates(), $converter->getBase());
```

Converter instances are immutable. To change the base currency, use the `->base(string $base)` method. To change the rates, make a new object.

[oer-signup]: https://openexchangerates.org/sign-up
