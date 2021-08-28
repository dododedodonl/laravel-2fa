<?php

namespace Dododedodonl\Laravel2fa\Facades;

use Illuminate\Support\Facades\Facade;

class TwoFactor extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-2fa';
    }
}
