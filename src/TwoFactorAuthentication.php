<?php

namespace Dododedodonl\Laravel2fa;

use Exception;
use Illuminate\Foundation\Application;

use Illuminate\Support\Str;

class TwoFactorAuthentication
{
    use Traits\SharedMethods, Traits\ResolvesRequired;

    protected $app;

    static protected $defaultViewsBase = 'dododedodonl::2fa.';

    /**
     * Sets the default views base to bootstrap 3 views
     *
     * @param string $base new view base
     */
    static public function useBootstrapThree()
    {
        static::setDefaultViewsBase('dododedodonl::2fa.bootstrap3');
    }

    /**
     * Sets the default views base
     *
     * @param string $base new view base
     */
    static public function setDefaultViewsBase($base)
    {
        static::$defaultViewsBase = Str::finish($base, '.');
    }

    public static function isRequired(): bool
    {
        return app()->call(static::$requiredResolver ?? static::defaultRequiredResolver());
    }

    /**
     * Get the full view name with base
     *
     * @param string $view
     * @return string
     */
    public function getViewName($view)
    {
        return self::$defaultViewsBase.$view;
    }

    /**
     * Get the laravel view with base
     *
     * @param string $view
     * @return view
     */
    public function view($view)
    {
        return view($this->getViewName($view));
    }

    /**
     * Constructor
     *
     * @author Tom
     * @param  \Illuminate\Foundation\Application $app
     */
    public function __construct(Application $app) {
        $this->app = $app;
    }

    /**
     * Return a query of the user
     *
     * @return query
     */
    public function userQuery()
    {
        $userModel = $this->userModel();
        return ( new $userModel() )->query();
    }

    /**
     * User model fqdn
     *
     * @return string  fqdn
     */
    public function userModel(): string
    {
        return config('laravel-2fa.user-model');
    }

    /**
     * When this is set to false, the setup routes won't work
     *
     * @return boolean
     */
    public function otpSetupEnabled(): bool
    {
        $enabled = config('laravel-2fa.setup-enabled');

        // The web setup depends on the imagick extension
        if ($enabled && ! class_exists(\Imagick::class)) {
            throw new Exception('You need to install the imagick extension to use the web setup.');
        }

        return $enabled;
    }

    /**
     * Is the user validated with 2fa?
     *
     * @author Tom
     * @return bool validated
     */
    public function validated(): bool
    {
        return $this->validateTime($this->app->request);
    }
}
