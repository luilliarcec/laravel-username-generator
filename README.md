# Laravel Username Generator

![Run Tests](https://github.com/luilliarcec/laravel-username-generator/workflows/Run%20Tests/badge.svg?branch=master)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/luilliarcec/laravel-username-generator.svg)](https://packagist.org/packages/luilliarcec/laravel-username-generator)
[![Quality Score](https://img.shields.io/scrutinizer/g/luilliarcec/laravel-username-generator)](https://scrutinizer-ci.com/g/luilliarcec/laravel-username-generator)
[![Total Downloads](https://img.shields.io/packagist/dt/luilliarcec/laravel-username-generator)](https://packagist.org/packages/luilliarcec/laravel-username-generator)
[![GitHub license](https://img.shields.io/github/license/luilliarcec/laravel-username-generator)](https://github.com/luilliarcec/laravel-username-generator/blob/develop/LICENSE.md)

Laravel Username Generator is a package that allows the versatile generation of user names, 
has a simple integration with Laravel.

The user generation is given by two types of controllers that can be defined in the 
`config\laravel-username-generator.php` file. 
You can generate from the name of the user, taking into account that you do not use more than two names and 
two surnames in total. It can also be generated from the user's email.

## Installation

You can install the package via composer:

```bash
composer require luilliarcec/laravel-username-generator
```

Now publish the configuration file into your app's config directory, by running the following command:

```bash
php artisan vendor:publish --provider="Luilliarcec\LaravelUsernameGenerator\UsernameGeneratorServiceProvider"
```

Remember, if you use Laravel 8, configure your configuration file `laravel-username-generator.php` and in 
model replace the default with `\App\Models\User`

That is all. 😀

## Usage

You can use the exposed Facade 
`Luilliarcec\LaravelUsernameGenerator\Facades\Username` for faster use, 
or use the class `Luilliarcec\LaravelUsernameGenerator\Support\UsernameGenerator`

```php
use Luilliarcec\LaravelUsernameGenerator\Facades\Username;

$username = Username::make('Luis Andrés Arce Cárdenas'); // larcec
```

You can change the presentation of your username to uppercase from the configuration file. 
`config\laravel-username-generator.php`

```php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Driver generator
    |--------------------------------------------------------------------------
    |
    | Generate user names by person names or by emails (name or email)
    |
    */

    'driver' => 'name',

    /*
    |--------------------------------------------------------------------------
    | Type Case
    |--------------------------------------------------------------------------
    |
    | The final result of the username will depend on the case selected. (lower or upper)
    |
    */

    'case' => 'lower',

    /*
    |--------------------------------------------------------------------------
    | Model Eloquent
    |--------------------------------------------------------------------------
    |
    | The model who will have a username, to avoid repeated.
    |
    */

    'model' => '\App\User',

    /*
    |--------------------------------------------------------------------------
    | Column Model Database
    |--------------------------------------------------------------------------
    |
    | Name of the column where the username is stored.
    |
    */

    'column' => 'username'
];
```

Defining in your case key "upper"

```php
<?php

return [
    /***/

    'case' => 'upper',
];
```

According to the previous example, the resulting value will be

```php
use Luilliarcec\LaravelUsernameGenerator\Facades\Username;

$username = Username::make('Luis Andrés Arce Cárdenas'); // LARCEC
```

#### ¡Important!
Define in your configuration file the Eloquent Model to whom the username will be 
generated and the column where that username is stored, so that the package is able to 
generate an alternative if said username is already in use.

Skipping this step will cause an exception `UsernameGeneratorException` or that the genarator does not work properly

###### Example

```php
<?php

return [
    /***/

    'model' => '\App\User',

    'column' => 'username'
];
```

Assume you have a user with the username `larcec`

```php
use Luilliarcec\LaravelUsernameGenerator\Facades\Username;

$username = Username::make('Luis Andrés Arce Cárdenas'); // larcec
```

When using the package to generate the username, it will search thanks to Eloquent, 
in the database and will buy if that username already exists, if it exists, a pefix will be added to the username.

The result would be as follows.

```php
use Luilliarcec\LaravelUsernameGenerator\Facades\Username;

$username = Username::make('Luciano Carlos Arce Cajamarca'); // larcec1
```

Laravel Username Generator uses a convention for the creation of user names, takes the `first letter of the first name`, 
takes the `first last name`, and finally the `first letter of the second last name`

However, Laravel Username Generator is so versatile that it can receive `only 1 name`, `1 name and 2 surnames`, 
and can even use the auxiliary surname parameter to pass the `two surnames separately`, in the following ways.

```php
use Luilliarcec\LaravelUsernameGenerator\Facades\Username;


/* Names and surnames separated */
$username = Username::make('Luis Andrés', 'Arce Cárdenas'); // larcec

/* One name and one surnames */
$username = Username::make('Luis Arce'); // larce
$username = Username::make('Luis', 'Arce'); // larce

/* One name and two surnames */
$username = Username::make('Luis Andrés', 'Arce'); // larce
$username = Username::make('Luis', 'Arce Cárdenas'); // larcec
$username = Username::make('Luis Arce Cárdenas'); // larcec

/* Full name */
$username = Username::make('Luis Andrés Arce Cárdenas'); // larcec
```

Keep these examples in mind, since passing a value of more or more than two names or 
two surnames without following the convention may cause an exception

Finally you can use the `email` driver, which will receive an email as the first and only parameter 
and take the user's email and use it as a username.

```php
use Luilliarcec\LaravelUsernameGenerator\Facades\Username;

$username = Username::make('larcec@test.com'); // larcec
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email luilliarcec@gmail.com instead of using the issue tracker.

## Credits

- [Luis Andrés Arce C.](https://github.com/luilliarcec)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
