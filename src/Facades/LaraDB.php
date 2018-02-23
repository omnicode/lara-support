<?php

namespace LaraSupport\Facades;

use Illuminate\Support\Facades\Facade;

class LaraDB extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'lara-db';
    }
}