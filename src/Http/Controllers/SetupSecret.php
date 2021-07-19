<?php

namespace dododedodonl\laravel2fa\Http\Controllers;

use dododedodonl\laravel2fa\Facades\TwoFactor;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use dododedodonl\laravel2fa\Traits\SharedMethods;

use BaconQrCode\Writer;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;

class SetupSecret
{
    use SharedMethods;

    /**
     * To qr code
     *
     * @param  string to convert to qr code
     * @return string base64 encoded qr code png
     */
    protected function base64QrCode($string) {
        $w = new Writer(new ImageRenderer(
            new RendererStyle(400),
            new ImagickImageBackEnd()
        ));

        return base64_encode($w->writeString($string));
    }

    /**
     * Gets the user or aborts with a 404 or 403.
     *
     * @param  \Illuminate\Http\Request $request
     * @return object
     */
    protected function getUserOrAbort(Request $request)
    {
        if(! resolve('laravel-2fa')->otpSetupEnabled()) {
            abort(404);
        }

        return $request->user();
    }

    /**
     * Generate token
     *
     * @param  string|null $secret
     * @return array
     */
    protected function createToken($user, $secret = null)
    {
        $labelProperty = config('laravel-2fa.user-name', 'name');

        $otp = $this->newOtp($user->{$labelProperty}, $secret);

        $qrCode = $this->base64QrCode($otp->getProvisioningUri());

        return [
            $otp->getSecret(),
            $qrCode,
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = $this->getUserOrAbort($request);
        abort_if(! is_null($user->otp_secret), 403, 'A secret is already set.');

        list($secret, $qrCode) = $this->createToken($user);

        $request->session()->flash('_2fa.secret', $secret);

        return resolve('laravel-2fa')->view('setup')->withBase64QrCode($qrCode);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $this->getUserOrAbort($request);
        abort_if(! is_null($user->otp_secret), 403, 'A secret is already set.');

        if( ! $request->session()->has('_2fa.secret')) {
            return redirect()->route('2fa.setup');
        }

        $user->otp_secret = $request->session()->get('_2fa.secret');

        $verified = $this->verifyTokenAndStoreTime(
            $request,
            $user,
            $request->input('2fa_token')
        );

        if($verified) {
            $user->save();
            return redirect($request->session()->pull('_2fa.intended', config('laravel-2fa.setup-default-redirect', '/')));
        }

        $request->session()->keep('_2fa.secret');

        list($secret, $qrCode) = $this->createToken($user, $request->session()->get('_2fa.secret'));

        return resolve('laravel-2fa')->view('setup')->withBase64QrCode($qrCode)->withErrors(['2fa_token' => 'Please verify again (qr-code is the same).']);
    }

    /**
     * Show the form for updating 2FA
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $user = $this->getUserOrAbort($request);
        abort_if(is_null($user->otp_secret), 403, 'A secret is not set yet.');

        list($secret, $qrCode) = $this->createToken($user);

        $request->session()->flash('_2fa.secret', $secret);

        return resolve('laravel-2fa')->view('edit')->withBase64QrCode($qrCode);
    }

    /**
     * Update 2FA token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = $this->getUserOrAbort($request);
        abort_if(is_null($user->otp_secret), 403, 'A secret is not set yet.');

        if( ! $request->session()->has('_2fa.secret')) {
            return redirect()->route('2fa.edit');
        }

        $errors = [];
        if ($this->verifyToken($user, $request->input('old_2fa_token'))) {
            $user->otp_secret = $request->session()->get('_2fa.secret');

            $verified = $this->verifyTokenAndStoreTime(
                $request,
                $user,
                $request->input('new_2fa_token')
            );

            if($verified) {
                $user->save();
                return redirect($request->session()->pull('_2fa.intended', config('laravel-2fa.setup-default-redirect', '/')));
            }

            $errors['new_2fa_token'] = 'Please verify again (qr-code is the same).';
        } else {
            $errors['old_2fa_token'] = 'Please enter a valid token to continue (qr-code is the same)';
        }

        $request->session()->keep('_2fa.secret');

        list($secret, $qrCode) = $this->createToken($user, $request->session()->get('_2fa.secret'));

        return resolve('laravel-2fa')->view('edit')->withBase64QrCode($qrCode)->withErrors($errors);
    }

    public function destroy(Request $request)
    {
        $user = $this->getUserOrAbort($request);
        abort_if(is_null($user->otp_secret), 403, 'A secret is not set yet.');
        abort_if(TwoFactor::isRequired(), 403, '2FA is required for some of your permissions, removing 2FA is not permitted.');

        if (! $this->verifyToken($user, $request->input('2fa_token'))) {
            return redirect()->route('2fa.edit')->withErrors(['2fa_token' => 'Please enter a valid token to confirm.']);
        }

        $user->otp_secret = null;
        $user->save();

        return redirect($request->session()->pull('_2fa.intended', config('laravel-2fa.setup-default-redirect', '/')));
    }
}
