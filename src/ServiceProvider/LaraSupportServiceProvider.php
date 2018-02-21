<?php

namespace LaraSupport\ServiceProvider;

use LaraSupport\LaraDB;
use LaraSupport\LaraServiceProvider;
use LaraSupport\LaraPassword;

class LaraSupportServiceProvider extends LaraServiceProvider
{
    /**
     *
     */
    public function boot()
    {
        $this->mergeConfig(__DIR__);
    }

    /**
     *
     */
    public function register()
    {
        $this->registerAliases(
            [
                'LaraDB' => \LaraSupport\Facades\LaraDB::class,
                'LaraPassword' => \LaraSupport\Facades\LaraPassword::class,
            ]
        );
        $this->registerSingletons([
            'lara-db' => LaraDB::class,
            'lara-password' => LaraPassword::class,
        ]);
    }
}
