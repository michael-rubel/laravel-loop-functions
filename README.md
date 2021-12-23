![Laravel Model Mapper](https://user-images.githubusercontent.com/37669560/147101154-e70d8648-ffa3-48c6-b9a8-6a072b7c1f00.png)

# Laravel Model-Property Mapper
[![Latest Version on Packagist](https://img.shields.io/packagist/v/michael-rubel/laravel-model-mapper.svg?style=flat-square&logo=packagist)](https://packagist.org/packages/michael-rubel/laravel-model-mapper)
[![Total Downloads](https://img.shields.io/packagist/dt/michael-rubel/laravel-model-mapper.svg?style=flat-square&logo=packagist)](https://packagist.org/packages/michael-rubel/laravel-model-mapper)
[![Code Quality](https://img.shields.io/scrutinizer/quality/g/michael-rubel/laravel-model-mapper.svg?style=flat-square&logo=scrutinizer)](https://scrutinizer-ci.com/g/michael-rubel/laravel-model-mapper/?branch=main)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/michael-rubel/laravel-model-mapper.svg?style=flat-square&logo=scrutinizer)](https://scrutinizer-ci.com/g/michael-rubel/laravel-model-mapper/?branch=main)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/michael-rubel/laravel-model-mapper/run-tests/main?style=flat-square&label=tests&logo=github)](https://github.com/michael-rubel/laravel-model-mapper/actions)
[![PHPStan](https://img.shields.io/github/workflow/status/michael-rubel/laravel-model-mapper/phpstan/main?style=flat-square&label=larastan&logo=laravel)](https://github.com/michael-rubel/laravel-model-mapper/actions)

This package provides functionality to map your model attributes to local class properties with the same names.

The package requires PHP `^8.x` and Laravel `^8.67`.

[![PHP Version](https://img.shields.io/badge/php-^8.x-777BB4?style=flat-square&logo=php)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/laravel-^8.67-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![Laravel Octane Compatible](https://img.shields.io/badge/octane-compatible-success?style=flat-square&logo=laravel)](https://github.com/laravel/octane)

## Installation
Install the package using composer:
```bash
composer require michael-rubel/laravel-model-mapper
```

## Usage
```php
use WithModelMapping;
```

Then in constructor or any other method:
```php
$this->mapModelAttributes($model);
```

This is especially handy with Livewire components when you want to map your model data to the view.

For example:
```php
class CompanyProfile extends Component
{
    use WithModelMapping;

    /**
     * Frontend properties.
     *
     * @var string|null
     */
    public ?string $name       = null;
    public ?string $tax_number = null;
    public ?string $address    = null;

    /**
     * @param \App\Models\Company $company 
     *
     * @return void
     */
    public function mount(Company $company): void
    {
        $this->mapModelAttributes($company);
    }
}
```

## Why?
Why should I use it like this instead of just passing the model to the view?
Because the models are huge objects and you probably shouldn't expose them to the frontend for security and performance reasons. Another thing is primitive view variables are highly customizable through view composers, while it's harder to decorate in the case of using models.

## Testing
```bash
composer test
```
