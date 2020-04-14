<?php

return [
    /*
     * Wether or not to enable the web setup.
     * Note: this requires ext-imagick, and therefore disabled by default
     */
    'setup-enabled' => env('OTP_SETUP_ENABLED', false),

    /*
     * Default place to redirect to after setup
     * Value is directly provided to redirect() as first argument
     * Note: this redirect is only used if no intended location was found
     */
    'setup-default-redirect' => '/',

    /*
     * Default place to redirect to after a token is provided
     * Value is directly provided to redirect() as first argument
     * Note: this redirect is only used if no intended location was found
     */
    'provide-default-redirect' => '/',

    /*
     * Routes that are allowed without the force directive
     * All routes within the '2fa' group (eg. the name starting with '2fa.') will be allowed automatically
     * Note: this should include the logout route
     */
    'allowed-routes' => ['logout'],

    /*
     * Your fqdn to the user model.
     */
    'user-model' => 'App\\User',

    /*
    * Property on the user model used as OTP label
    */
    'user-name' => 'name',

    /*
     * Name used as OTP issuer
     */
    'otp-issuer' => config('app.name', 'Laravel 2fa'),

    /*
     * Path that is supplied to @extends() in the blade templates
     */
    'blade-extends' => 'layouts.app',

    /*
     * Number of minutes without activity to revalidate with a token request.
     * Set to 0 to disable the refresh timeout.
     */
    'refresh-timeout' => env('OTP_REFRESH_TIMEOUT', 15), // minutes

    /*
     * Size of the token window used for verification to allow time variance between user and server
     * Default: 6, this allows for a little time variance (6 times 30 seconds)
     */
    'token-verification-window' => env('OTP_TOKEN_VERIFICATION_WINDOW', 6), // number of tokens
];
