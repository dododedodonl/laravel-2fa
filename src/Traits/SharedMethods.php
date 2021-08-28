<?php

namespace dododedodonl\laravel2fa\Traits;

use OTPHP\TOTP;
use Illuminate\Http\Request;


trait SharedMethods
{
    /**
     * Verifies a token
     *
     * @param object $user
     * @param string $token to be verified token
     * @param int $window (optional) how big is the verify window
     * @return boolean valid or not
     */
    protected function verifyToken(object $user, string $token, $window=null) {
        $window = $window ?? config('laravel-2fa.token-verification-window');
        $otp = TOTP::create($user->otp_secret);

        return $otp->verify($token, null, $window);
    }

    /**
     * Verifies a token and sets time session
     *
     * @param object $user
     * @param string $token to be verified token
     * @return boolean valid or not
     */
    protected function verifyTokenAndStoreTime($request, object $user, string $token) {
        $window = config('laravel-2fa.token-verification-window');

        $otp = TOTP::create($user->otp_secret);

        $verify = $otp->verify($token, null, $window);

        if($verify === true) {
            $request->session()->put('_2fa.time', now());
        }

        return $verify;
    }

    /**
     * Verifies a token
     *
     * @param string|null $label
     * @param string|null $secret if null a new secret is generated
     * @param string|null $issuer defaults to app.name
     * @return OTPHP\TOTP totp object
     */
    protected function newOtp($label = null, $secret = null, $issuer = null) {
        $otp =  TOTP::create($secret);

        if(is_null($issuer)) {
            $issuer = config('laravel-2fa.otp-issuer');
        }
        $otp->setIssuer($issuer);

        if(is_null($label)) {
            $label = '';
        }
        $otp->setLabel($label);

        return $otp;
    }

    /**
     * Verifies the time session and resets or removes it
     *
     * @param Request $request
     * @return boolean wether or not the time is valid
     */
    protected function validateTime($request) {
        if($request->session()->has('_2fa.time')) {
            $time = $request->session()->get('_2fa.time');

            $timeout = config('laravel-2fa.refresh-timeout');
            if($timeout > 0 && $time->diffInMinutes(now()) > $timeout) {
                //After 15 minutes of inactivity, require a new 2fa token
                $request->session()->pull('_2fa.time');
            } else {
                //Otherwise just carry on
                $request->session()->put('_2fa.time', now());
                return true;
            }
        }

        return false;
    }

    /**
     * Checks setup
     *
     * @param Request $request
     * @return boolean wether or not the user has to be setup
     */
    protected function isSetup($request) {
        if($request->user()) {
            if( ! is_null($request->user()->otp_secret)) {
                return true;
            }
        }

        return false;
    }
}
