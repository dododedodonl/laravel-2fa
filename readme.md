# laravel-2fa

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

## Installation

### Step 1

Install using composer

``` bash
composer require dododedodonl/laravel-2fa
```

### Step 2

Publish migration and run it. Change the migration if it does not fit your database schema.

``` bash
php artisan vendor:publish --tag "laravel-2fa.migrations"
php artisan migrate
```


### Step 3

Either configure web-based secret setup (by enabling the `php-imagick` extension), or make an error message visible when a user has no secret set manually using artisan.

#### Error on login
Edit your login form page, and add this somewhere when secret setup via web is disabled to display the correct errors.
``` blade
@error('otp_error')
<div class="alert alert-danger" role="alert">{{ $message }}</div>
@enderror
```

### Vendor assets
Optionally publish config, migration or views

``` bash
# Some
php artisan vendor:publish --tag "laravel-2fa.config"
php artisan vendor:publish --tag "laravel-2fa.migrations"
php artisan vendor:publish --tag "laravel-2fa.views"

# All
php artisan vendor:publish --provider "Dododedodonl\Laravel2fa\TwoFactorAuthenticationServiceProvider"
```

## Usage

### Protect a route
A middleware alias is added called `2fa`. You can assign this to individual routes or controllers like all other middleware.

``` php
Route::get('home', 'HomeController@index')->name('home')->middleware('2fa');
```
#### Disabled by default
The middleware is disabled by default in some cases (for example in local environment). Override this by using `2fa:force` as middleware.

### Globally
To use it globally, add `\Dododedodonl\Laravel2fa\Http\Middleware\Verify2faAuth` to the `web` group in your `app/Http/kernel.php`. Routes starting with `2fa.` and the route `logout` will still work when logged in. On all other routes the middleware will be applied and a token will be asked.

## Secret setup

### Via web
This is disabled by default because it requires `ext-imagick` php extension. Edit `config/laravel-2fa.php` or edit your environment file.
Add `OTP_SETUP_ENABLED=true` to your `.env` file to enable.

### Via artisan
Set a secret for a user: `php artisan 2fa:generate {username}`.
Revoke a secret for a user: `php artisan 2fa:revoke {username}`.

## Troubleshoot

### No token is asked of me
By default, the middleware is disabled when the environment is set to local to make testing easier. Use `2fa:force` to force the execution of the middelware.

### I get redirected back to the login page without error
When no secret is found in the database, and web-based secret setup is not configured, you are redirected back to the login page, logged out.
An error does accompany this, but you need to edit your `login.blade.php` file to show it as suggested in one of the installation steps.

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email dododedodonl@thor.edu instead of using the issue tracker.

## Notes

This packages assumed you use Bootstrap 4 as css framework. Bootstrap 3 views are also provided, configure them by calling `Dododedodonl\Laravel2fa\TwoFactorAuthentication::useBootstrapThree()`.
However, this is not a requirement, you can just change the views to your css framework.

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/dododedodonl/laravel-2fa.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/dododedodonl/laravel-2fa.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/dododedodonl/laravel-2fa/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/dododedodonl/laravel-2fa
[link-downloads]: https://packagist.org/packages/dododedodonl/laravel-2fa
[link-travis]: https://travis-ci.org/dododedodonl/laravel-2fa
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/dododedodonl
[link-contributors]: ../../contributors
