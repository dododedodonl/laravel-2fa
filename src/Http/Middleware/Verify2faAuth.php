<?php

namespace Dododedodonl\Laravel2fa\Http\Middleware;

use Illuminate\Support\Str;
use Dododedodonl\Laravel2fa\Traits\SharedMethods;

use Closure;
use Illuminate\Support\Facades\Auth;

class Verify2faAuth
{
    use SharedMethods;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string    $force when equal to 'force', force 2fa middleware
     * @return mixed
     */
    public function handle($request, Closure $next, $force = null) {
        if(( ! $request->user() || ! $request->session()) && $force != 'force') {
            //When there is no user, no session or no force flag, carry on
            return $next($request);
        }

        //Only validate when force is set
        if(app()->environment('local') && $force != 'force') {
            return $next($request);
        }

        //Check if there is a time and validate it
        if($this->validateTime($request)) {
            return $next($request);
        }

        $name = $request->route()->getName();
        if(Str::startsWith($name, '2fa.') || \in_array($name, config('laravel-2fa.allowed-routes', ['logout']))) {
            //Make sure it is possible to reach the 2fa routes and the logout route
            return $next($request);
        }

        //Check if the user is set up
        if( ! $this->isSetup($request)) {
            if (! resolve('laravel-2fa')::isRequired() && $force != 'force') {
                // 2fa is optional for this user if not setup unless the force flag is set
                return $next($request);
            }

            //Check if self setup is possible
            if ( ! resolve('laravel-2fa')->otpSetupEnabled()) {
                //Logout and redirect to login page with an error
                Auth::logout();

                return redirect()->route('login')->withErrors(['otp_error' => 'Contact an admin to setup your two factor authentication.']);
            } else {
                //Redirect to setup page
                return redirect()->route('2fa.setup');
            }
        }

        //Save intended url in order to route back after validation
        $intended = $request->getRequestUri();
        if(Str::endsWith($intended, 'login') || Str::contains($intended, '/2fa/')) {
            $intended = '/';
        }
        $request->session()->put('_2fa.intended', $intended);

        //Redirect to provide 2fa
        return redirect()->route('2fa.provide');
    }
}
