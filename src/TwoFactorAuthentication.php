<?php

namespace dododedodonl\laravel2fa;

use Exception;
use Illuminate\Foundation\Application;

class TwoFactorAuthentication
{
    use Traits\SharedMethods;

    protected $app;

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
