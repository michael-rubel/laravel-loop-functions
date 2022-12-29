![Laravel Loop Functions](https://user-images.githubusercontent.com/37669560/197970105-a3ea9090-ebae-495b-ac13-729b0565d75a.png)

# Laravel Loop Functions
[![Latest Version on Packagist](https://img.shields.io/packagist/v/michael-rubel/laravel-loop-functions.svg?style=flat-square&logo=packagist)](https://packagist.org/packages/michael-rubel/laravel-loop-functions)
[![Total Downloads](https://img.shields.io/packagist/dt/michael-rubel/laravel-loop-functions.svg?style=flat-square&logo=packagist)](https://packagist.org/packages/michael-rubel/laravel-loop-functions)
[![Code Quality](https://img.shields.io/scrutinizer/quality/g/michael-rubel/laravel-loop-functions.svg?style=flat-square&logo=scrutinizer)](https://scrutinizer-ci.com/g/michael-rubel/laravel-loop-functions/?branch=main)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/michael-rubel/laravel-loop-functions.svg?style=flat-square&logo=scrutinizer)](https://scrutinizer-ci.com/g/michael-rubel/laravel-loop-functions/?branch=main)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/michael-rubel/laravel-loop-functions/run-tests.yml?branch=main&style=flat-square&label=tests&logo=github)](https://github.com/michael-rubel/laravel-loop-functions/actions)
[![PHPStan](https://img.shields.io/github/actions/workflow/status/michael-rubel/laravel-loop-functions/phpstan.yml?branch=main&style=flat-square&label=larastan&logo=laravel)](https://github.com/michael-rubel/laravel-loop-functions/actions)

The package provides the collection of methods to loop over your data.

The package requires PHP `^8.x` and Laravel `^9.0`.

## #StandWithUkraine
[![SWUbanner](https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/banner2-direct.svg)](https://github.com/vshymanskyy/StandWithUkraine/blob/main/docs/README.md)

## Installation
Install the package using composer:
```bash
composer require michael-rubel/laravel-loop-functions
```

## Usage
```php
use LoopFunctions;
```

#### Assign Eloquent model attributes to class properties:
```php
$this->propertiesFrom($model);
```

#### Assign array key values to class properties:
```php
$this->propertiesFrom($array);
```

If you want to use dynamic properties, adjust the `dynamic_properties` key in the config and add the following trait if your class is not already implementing the `get/set` magic methods:
```php
use WithDynamicProperties;
```

`Note: if you use the Livewire components, it already has similar definitions under the hood.`

#### Dump class properties:
```php
$this->dumpProperties();
```

## Ignored property names
By default, the package ignores `id` and `password` properties to avoid conflicts in Livewire/auth components.
You can customize the ignore list by editing the config.

```bash
php artisan vendor:publish --tag="loop-functions-config"
```

## Logging
The functions don't throw an exception in case of failed assignment (e.g. type incompatibility) but log such an event.
You can disable exception logging if you wish so in the config.

## Testing
```bash
composer test
```

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
