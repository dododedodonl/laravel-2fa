<?php

namespace dododedodonl\laravel2fa;

use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class TwoFactorAuthenticationServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(Filesystem $filesystem)
    {
        if(\version_compare($this->app->version(), '5.8.13', '<')) {
            $this->bootBlade();
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'dododedodonl');

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
            $this->bootForConsole($filesystem);
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
    protected function bootForConsole(Filesystem $filesystem)
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/laravel-2fa.php' => config_path('laravel-2fa.php'),
        ], 'laravel-2fa.config');

        // Publishing the migration.
        $this->publishes([
            __DIR__.'/../database/migrations/add_otp_secret_to_users_table.php.stub' => $this->getMigrationFileName($filesystem),
        ], 'laravel-2fa.migrations');

        // Publishing view.
        $this->publishes([
           __DIR__.'/../resources/views' => resource_path('views/vendor/dododedodonl'),
       ], 'laravel-2fa.views');



        // Register commands
        $this->commands([
            Console\GenerateOtpSecret::class,
            Console\RevokeOtpSecret::class,
        ]);
    }

    /**
     * Add the blade error directive
     *
     * @return void
     */
    protected function bootBlade()
    {
        Blade::directive('error', function($expression) {
            return '<?php if ($errors->has('.$expression.')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('.$expression.'); ?>';
        });

        Blade::directive('enderror', function($expression) {
            return '<?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>';
        });
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @param Filesystem $filesystem
     * @return string
     */
    protected function getMigrationFileName(Filesystem $filesystem): string
    {
        $timestamp = date('Y_m_d_His');

        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem) {
                return $filesystem->glob($path.'*_add_otp_secret_to_users_table.php');
            })->push($this->app->databasePath()."/migrations/{$timestamp}_add_otp_secret_to_users_table.php")
            ->first();
    }
}
