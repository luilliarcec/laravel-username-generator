# Laravel Username Generator

[![run-tests](https://github.com/luilliarcec/laravel-username-generator/actions/workflows/run-tests.yml/badge.svg)](https://github.com/luilliarcec/laravel-username-generator/actions/workflows/run-tests.yml)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/luilliarcec/laravel-username-generator.svg)](https://packagist.org/packages/luilliarcec/laravel-username-generator)
[![Quality Score](https://img.shields.io/scrutinizer/g/luilliarcec/laravel-username-generator)](https://scrutinizer-ci.com/g/luilliarcec/laravel-username-generator)
[![Total Downloads](https://img.shields.io/packagist/dt/luilliarcec/laravel-username-generator)](https://packagist.org/packages/luilliarcec/laravel-username-generator)
[![GitHub license](https://img.shields.io/github/license/luilliarcec/laravel-username-generator)](https://github.com/luilliarcec/laravel-username-generator/blob/develop/LICENSE.md)

<a href="https://www.buymeacoffee.com/luilliarcec" target="_blank"><img src="https://cdn.buymeacoffee.com/buttons/default-orange.png" alt="Buy Me A Coffee" height="41" width="174"></a>

Laravel Username Generator is a package that allows the versatile generation of usernames, has a simple integration
with Laravel.

You can generate from the name of the user, taking into account that you do not use more than two names and two surnames
in total. It can also be generated from the user's email.

## Installation

You can install the package via composer:

### Version 5 was rewritten to be supported as a usable trait within your models.

### Please follow this guide if you are going to update to the new version.

```bash
composer require luilliarcec/laravel-username-generator
```

## Upgrade

Upgrading to the new version is as easy as:

- Update package
- Delete the old configuration from your AppServiceProvider.

## Usage

Add the Trait `Luilliarcec\LaravelUsernameGenerator\Concerns\HasUsername` to your Eloquent models in the
use username.

Remember that you must have a field in your table where you can store the `username`, preferably it is recommended
make this field `unique`.

```php
use Illuminate\Database\Eloquent\Model;
use Luilliarcec\LaravelUsernameGenerator\Concerns\HasUsername;

class User extends Model
{
    use HasUsername;
}
```

Within your model, you configure the username generator options.

By default, you must configure the field where the username will be stored and searched:

```php
protected function getUsernameColumn(): string
{
    return 'my_username_column';
}
```

In addition to where the value will be extracted to generate the username:

```php
protected function getName(): string
{
    // This is the value, not the field name.
    return $this->name;
}
```

**Remember the value of name cannot be empty `''`, this will throw an exception, just like using a driver 
incorrect for an incorrect value, for example using the `Name` driver to generate usernames from an email.**

With this, your model will now be configured to work with usernames.

### Additional settings

If your model stores the first and last name separately, you can set the `getLastName` function, so that it returns
the value of your model's last name.

```php
protected function getLastName(): ?string
{
    // This is the value, not the field name.
    return $this->last_name;
}
```

By default, the trait uses the driver `Luilliarcec\Laravel Username Generator\Drivers\Name`. This is modifiable
overriding the `getUsernameDriver` method.

```php
use Luilliarcec\LaravelUsernameGenerator\Drivers\Email;

protected function getUsernameDriver(): DriverContract
{
    return new Email();
}
```

By default, usernames are converted to lowercase. But if you want, convert them to uppercase or apply
some logic or add a prefix or suffix, before the repeat search is processed, you can use the function
`transformUsername`, this function receives the username as a parameter and returns a string.

```php
protected function transformUsername(string $username): string
{
    return mb_strtoupper($username, 'UTF-8');
}
```

#### Support for customs drivers

You can create a class that implement the
interface `Luilliarcec\LaravelUsernameGenerator\Contracts\DriverContract`
and inside that class you can write all the logic to generate your username, remember to implement the make method that
will be responsible for returning the username, for example:

```php
namespace App\Support\Username\Drivers;

use Luilliarcec\LaravelUsernameGenerator\Contracts\DriverContract;

class CustomDriver implements DriverContract
{
    public function make(string $name, string $lastname = null): string
    {
        // your code
    }
}
```

## ¡Important!

Remember that like previous versions it is very important that you provide an Eloquent Model together with the column
that stores the username. This is so that the package provides you with an alternate username if it is already in use.

Skipping this step will cause an exception `UsernameGeneratorException` or that the generator does not work properly

## Examples

Assume you have a user with the username `larcec`

```php
$model = User::create(['name' => 'Luis Andrés Arce Cárdenas']);

$model->username; // larcec
```

When using the package to generate the username, it will search thanks to Eloquent, in the database and will buy if that
username already exists, if it exists, a suffix will be added to the username.

The result would be as follows.

```php
$model = User::create(['name' => 'Luciano Carlos Arce Cajamarca']);

$model->username; // larcec1
```

Laravel Username Generator uses a convention for the creation of user names, takes the `first letter of the first name`,
takes the `first last name`, and finally the `first letter of the second last name`

However, Laravel Username Generator is so versatile that it can receive `only 1 name`, `1 name and 2 surnames`, and can
even use the auxiliary surname parameter to pass the `two surnames separately`, in the following ways.

```php
$model = User::create(['first_name' => 'Luis Andrés', 'last_name' => 'Arce Cárdenas']);
$model = User::create(['name' => 'Luis Andrés Arce Cárdenas']);
// This will generate the following username: larcec

$model = User::create(['first_name' => 'Luis', 'last_name' => 'Arce']);
$model = User::create(['name' => 'Luis Arce']);
// This will generate the following username: larce

$model = User::create(['first_name' => 'Luis', 'last_name' => 'Arce Cárdenas']);
$model = User::create(['name' => 'Luis Arce Cárdenas']);
// This will generate the following username: larcec
```

Keep these examples in mind, since passing a value of more or more than two names or two surnames without following the
convention may cause an exception

Finally, you can use the `email` driver, which will receive an email as the first and only parameter and take the user's
email and use it as a username.

```php
$model = User::create(['email' => 'luilliarcec@gmail.com']);
// This will generate the following username: luilliarcec
```

## Testing

Can use docker-compose to run

``` bash
docker-compose exec app composer test
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
