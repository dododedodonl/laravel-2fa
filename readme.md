# laravel-2fa

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

## Installation

Via Composer

``` bash
composer require dododedodonl/laravel-2fa
php artisan migrate
```

Optionally publish config, migration or views

``` bash
# Some
php artisan vendor:publish --tag "laravel-2fa.config"
php artisan vendor:publish --tag "laravel-2fa.migrations"
php artisan vendor:publish --tag "laravel-2fa.views"

# All
php artisan vendor:publish --provider "dododedodonl\laravel2fa\TwoFactorAuthenticationServiceProvider"
```

### Error on login
Edit your login form page, and add this somewhere when secret setup via web is disabled to display the correct errors.
``` blade
@error('otp_error')
    <div class="alert alert-danger" role="alert">{{ $message }}</div>
@enderror
```

## Usage

### Per route
The package adds a middleware alias, named `2fa`.
``` php
Route::get('/', 'HomeController@index')->name('home')->middleware('2fa');
```
By default, the middleware is disabled in some cases (for example in local environment). To override this, use `2fa:force` as middleware option.

### Globally
To use it globally, add `\dododedodonl\laravel2fa\Http\Middleware\Verify2faAuth` to the `web` group in your `app/Http/kernel.php`. It's own routes and the routes named login and logout will still work when logged in. On all other routes the middleware will be applied.


## Secret setup

### Via web
This is disabled by default because it requires `ext-imagick` php extension. Edit `config/laravel-2fa.php` or edit your environment file.  
Add `OTP_SETUP_ENABLED=true` to your `.env` file  to enable.

### Via artisan
Set a secret for a user: `php artisan 2fa:generate {username}`.  
Revoke a secret for a user: `php artisan 2fa:revoke {username}`.

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email dododedodonl@thor.edu instead of using the issue tracker.

## Notes

This packages assumed you use Bootstrap 4 as css framework. However, this is not a requirement, you can just change the views to your css framework.

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
