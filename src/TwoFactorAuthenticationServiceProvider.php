<?php

namespace dododedodonl\laravel2fa;

use Illuminate\Support\ServiceProvider;

class TwoFactorAuthenticationServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'dododedodonl');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Register routes
        $this->app['router']->namespace('dododedodonl\\laravel2fa\\Http\\Controllers')
            ->middleware(['web'])
            ->group(function () {
                $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
            });

        // Register middleware
        $this->app['router']->aliasMiddleware('2fa', Http\Middleware\Verify2faAuth::class);

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-2fa.php', 'laravel-2fa');

        // Register the service the package provides.
        $this->app->singleton('laravel-2fa', function ($app) {
            return new TwoFactorAuthentication($app);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['laravel-2fa'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/laravel-2fa.php' => config_path('laravel-2fa.php'),
        ], 'laravel-2fa.config');

        // Publishing the migrations.
        $this->publishes([
            __DIR__.'/../database/migrations' => base_path('database/migrations'),
        ], 'laravel-2fa.migrations');

        // Publishing view.
        $this->publishes([
            __DIR__.'/../resources/views' => public_path('resources/views/vendor/dododedodonl'),
        ], 'laravel-2fa.views');

        // Register commands
        $this->commands([
            Console\GenerateOtpSecret::class,
            Console\RevokeOtpSecret::class,
        ]);
    }
}
