<?php

return [
    /*
     * Wether or not to enable the web setup.
     * Note: this requires ext-imagick
     */
    'setup-enabled' => env('OTP_SETUP_ENABLED', false),

    /*
     * Your fqdn to the user model.
     */
    'user-model' => 'App\\User',

    /*
     * Number of minutes without activity to revalidate with a token request.
     * Set to 0 to disable the refresh timeout.
     */
    'refresh-timeout' => env('OTP_REFRESH_TIMEOUT', 15), // minutes

    /*
     * Size of the token window used for verification to allow time variance.
     * Default: 6, this allows for a little time variance
     */
    'token-verification-window' => env('OTP_TOKEN_VERIFICATION_WINDOW', 6), // number of tokens
];
