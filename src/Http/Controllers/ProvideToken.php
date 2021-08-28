<?php

namespace Dododedodonl\Laravel2fa\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

use Dododedodonl\Laravel2fa\Traits\SharedMethods;

class ProvideToken
{
    use SharedMethods;

    /**
     * Display the form to provide the 2fa
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $secretSet = ! is_null($request->user()->otp_secret);
        if (! $secretSet && config('laravel-2fa.setup-enabled')) {
            return redirect()->route('2fa.setup');
        }

        return resolve('laravel-2fa')->view('provide')->with('secretSet', $secretSet);
    }

    /**
     * Process the provided 2fa
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $verified = $this->verifyTokenAndStoreTime(
            $request,
            $request->user(),
            $request->input('2fa_token')
        );

        if($verified) {
            return redirect($request->session()->pull('_2fa.intended', config('laravel-2fa.provide-default-redirect', '/')));
        }

        return resolve('laravel-2fa')->view('provide')->with('secretSet', true)->withErrors([
            '2fa_token' => 'Please enter a valid token.'
        ]);
    }
}
