<?php

namespace dododedodonl\laravel2fa\Traits;

use Closure;

/**
 * Trait ResolvesRequired
 *
 * set closure whether 2fa is optional or required
 *
 * true: middleware 2fa will force user to setup 2fa and use 2fa
 * false: middleware 2fa will only force user to use 2fa if the optional setup is completed
 */
trait ResolvesRequired
{
    /** @var callable|boolean|null */
    protected static $requiredResolver = null;

    /**
     * @param callable|boolean|null $callback
     * @return void
     */
    public static function resolveRequiredUsing($callback): void
    {
        if (is_bool($callback)) {
            $callback = function () use ($callback): bool {
                return $callback;
            };
        }

        static::$requiredResolver = $callback;
    }

    /**
     * Defaults to true
     * @return Closure
     */
    public static function defaultRequiredResolver(): Closure
    {
        return function (): bool {
            return true;
        };
    }
}
