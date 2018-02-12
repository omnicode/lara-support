<?php

namespace LaraSupport;

use Illuminate\Support\ServiceProvider;

class LaraServiceProvider extends ServiceProvider
{

    protected function getConstantsPath($path, $name = '')
    {
        $path = $this->getPackagePath($path) . 'config' . DIRECTORY_SEPARATOR;
        $path .= $name ? $name : 'constants.php';
        return $path;
    }

    protected function getFunctionsPath($path, $name = '')
    {
        $path = $this->getPackagePath($path) . 'config' . DIRECTORY_SEPARATOR;
        $path .= $name ? $name : 'constants.php';
        return $path;
    }


    protected function getConfigPath($config)
    {

    }

    protected function getViewPath($view)
    {

    }


    protected function getPackagePath($path)
    {
        return Str::before($path, 'src', 1);
    }
}
