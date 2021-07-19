# Laravel Username Generator

![Run Tests](https://github.com/luilliarcec/laravel-username-generator/workflows/Run%20Tests/badge.svg?branch=master)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/luilliarcec/laravel-username-generator.svg)](https://packagist.org/packages/luilliarcec/laravel-username-generator)
[![Quality Score](https://img.shields.io/scrutinizer/g/luilliarcec/laravel-username-generator)](https://scrutinizer-ci.com/g/luilliarcec/laravel-username-generator)
[![Total Downloads](https://img.shields.io/packagist/dt/luilliarcec/laravel-username-generator)](https://packagist.org/packages/luilliarcec/laravel-username-generator)
[![GitHub license](https://img.shields.io/github/license/luilliarcec/laravel-username-generator)](https://github.com/luilliarcec/laravel-username-generator/blob/develop/LICENSE.md)

Laravel Username Generator is a package that allows the versatile generation of user names, has a simple integration
with Laravel.

You can generate from the name of the user, taking into account that you do not use more than two names and two surnames
in total. It can also be generated from the user's email.

## Installation

You can install the package via composer:

### We have improved many things so we have decided to launch a new version 2.0.

### Please follow this guide if you are going to update to the new version.

```bash
composer require luilliarcec/laravel-username-generator
```

Now in AppServiceProvider, add the basic or default configuration to use.:

```php
namespace App\Providers;

use Luilliarcec\LaravelUsernameGenerator\Facades\Username;
use App\Models\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Username::withTrashed()
            ->setDriver('name') // By default 'name' is used so you can omit this if you like.
            ->setCase('lower') // By default 'lower' is used so you can omit this if you like.
            ->setModel(User::class) // By default 'App\Models\User' is used so you can omit this if you like.
            ->setColum('username'); // By default 'username' is used so you can omit this if you like.
            
        // If you want to use the defaults, it would look like this.
        
        Username::withTrashed()
            // If you are using another namespace for your User model, set it here.
            ->setModel('App\Entities\User');
    }
}
```

Note that now you are free to configure as you like from the Facade. You also have the possibility to tell the package,
to check with deletions in the model provided.

## Upgrade

Upgrading to the new version is as easy as:

- Update package
- Delete the configuration file
- And set the configuration from your AppServiceProvider.

## Usage

Once configured, you can use the Facade `Luilliarcec\LaravelUsernameGenerator\Facades\Username` in the following way:

```php
use Luilliarcec\LaravelUsernameGenerator\Facades\Username;

$username = Username::make('Luis Andrés Arce Cárdenas'); // larcec
```

If you want to change the type of case I can do it online, for example:

```php
use Luilliarcec\LaravelUsernameGenerator\Facades\Username;

$username = Username::setCase('upper')->make('Luis Andrés Arce Cárdenas'); // LARCEC
```

This will implicitly take the settings from your `AppServiceProvider` and replace it with the one you provide inline.

## New Features

One of the features that I liked to add the most is the possibility that you can create your own driver.

#### Support for customs drivers

You can create a class that implement the
interface `Luilliarcec\LaravelUsernameGenerator\Contracts\UsernameDriverContract`
and inside that class you can write all the logic to generate your username, remember to implement the make method that
will be responsible for returning the username, for example:

```php
namespace App\Support\Username\Drivers;

use Luilliarcec\LaravelUsernameGenerator\Contracts\UsernameDriverContract;

class CustomDriver implements UsernameDriverContract
{
    public function make(string $name, string $lastname = null): string
    {
        // your code
    }
}
```

Usage

```php
use Luilliarcec\LaravelUsernameGenerator\Facades\Username;
use App\Support\Username\Drivers\CustomDriver;

$username = Username::setDriver(new CustomDriver())->make('Luis Andrés Arce Cárdenas');

// Or

$username = Username::setDriver(CustomDriver::class)->make('Luis Andrés Arce Cárdenas');
```

#### Support for multiple models

That's right, you now have the ability to generate usernames for different models. Just pass the space name of your
model to the setModel function and you can even s et the column to use to check for existing usernames, for example:

```php
use Luilliarcec\LaravelUsernameGenerator\Facades\Username;
use App\Models\CustomModel;

$username = Username::setModel(CustomModel::class, 'other_column')->make('Luis Andrés Arce Cárdenas');

// or

$username = Username::setModel(CustomModel::class)->setColum('other_column')->make('Luis Andrés Arce Cárdenas');
```

#### Support for softdelete

And last but not least, if you want your generator to verify usernames with deleted users, now you can with the
withTrashed function.

```php
use Luilliarcec\LaravelUsernameGenerator\Facades\Username;
use App\Models\User;

Username::withTrashed()
            ->setDriver('name')
            ->setCase('lower')
            ->setModel(User::class)
            ->setColum('username');
```

But if you don't use softDelete or don't want to check with deleted users, use the withoutTrashed method. 
(By default this method is already applied)

```php
use Luilliarcec\LaravelUsernameGenerator\Facades\Username;
use App\Models\User;

Username::withoutTrashed()
            ->setDriver('name')
            ->setCase('lower')
            ->setModel(User::class)
            ->setColum('username');
```

## ¡Important!

Remember that like previous versions it is very important that you provide an Eloquent Model together with the column
that stores the username. This is so that the package provides you with an alternate username if it is already in use.

Skipping this step will cause an exception `UsernameGeneratorException` or that the genarator does not work properly

## Examples

Assume you have a user with the username `larcec`

```php
use Luilliarcec\LaravelUsernameGenerator\Facades\Username;

$username = Username::make('Luis Andrés Arce Cárdenas'); // larcec
```

When using the package to generate the username, it will search thanks to Eloquent, in the database and will buy if that
username already exists, if it exists, a pefix will be added to the username.

The result would be as follows.

```php
use Luilliarcec\LaravelUsernameGenerator\Facades\Username;

$username = Username::make('Luciano Carlos Arce Cajamarca'); // larcec1
```

Laravel Username Generator uses a convention for the creation of user names, takes the `first letter of the first name`,
takes the `first last name`, and finally the `first letter of the second last name`

However, Laravel Username Generator is so versatile that it can receive `only 1 name`, `1 name and 2 surnames`, and can
even use the auxiliary surname parameter to pass the `two surnames separately`, in the following ways.

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

Keep these examples in mind, since passing a value of more or more than two names or two surnames without following the
convention may cause an exception

Finally you can use the `email` driver, which will receive an email as the first and only parameter and take the user's
email and use it as a username.

```php
use Luilliarcec\LaravelUsernameGenerator\Facades\Username;

$username = Username::make('larcec@test.com'); // larcec
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email luilliarcec@gmail.com instead of using the issue tracker.

## Credits

- [Luis Andrés Arce C.](https://github.com/luilliarcec)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
