Money.php
---------

[![Travis](https://img.shields.io/travis/joyent/node.svg)](https://magnum.travis-ci.com/ameliaikeda/money)
[![Packagist](https://img.shields.io/packagist/dt/amelia/money.svg)](https://packagist.org/packages/amelia/money)
[![Current Version](https://img.shields.io/packagist/v/amelia/money.svg)](https://packagist.org/packages/amelia/money)
[![MIT License](https://img.shields.io/packagist/l/amelia/money.svg)](https://packagist.org/packages/amelia/money)

A PHP 5.4+ library for working with currency APIs and conversion.

Current Providers for data are:

* Open Exchange Rates (https://openexchangerates.org)
* Her Majesty's Revenue & Customs (http://www.hmrc.gov.uk/softwaredevelopers/2015-exrates.htm)

Feel free to add more!

Usage
=====

To dive in with the OpenExchangeRates API implementation, go [sign up for an app id][oer-signup]. It's free!

General Usage:

```php
<?php

use Amelia\Money\Converter;
use Amelia\Money\Api\OpenExchangeRates;

class Foo implements Bar {
    public function __construct() {
        $api = new OpenExchangeRates(getenv('FXRATES_APP_ID'));
        $this->converter = new Converter($api->getLatest());
    }
}
```

Converter instances are immutable. To change the base currency, use the `->base(string $base)` method. To change the rates, make a new object.

# Laravel 5

Money ships with a Laravel 5 service provider.

Simply add `"Amelia\Money\MoneyServiceProvider"` to your providers array.

You can type-hint `Amelia\Money\FactoryInterface` or use the `Amelia\Money\Facades\Money` Facade.

```php
<?php

use Amelia\Money\FactoryInterface;
use Money;

class Foo {
    public function __construct(FactoryInterface $money) {
        $money->convert($amount = 130.01, $from = "USD", $to = "GBP");
        Money::convert($amount, $from, $to);

        $money->getBase();
        Money::getBase();
        
        $money->getRates();
        Money::getRates();

        $newMoney = $money->base("GBP"); // switch the base currency
        $newMoney = Money::base("GBP");
    }
}
```

Add the following configuration to the `config/services.php` array

```php
    "money" => [
        "api" => "openexchangerates", // or "hmrc"
        "key" => YOUR_APP_ID,         // null if using HMRC
    ],
```

[oer-signup]: https://openexchangerates.org/sign-up
