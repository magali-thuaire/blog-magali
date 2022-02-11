<?php

namespace App;

class Autoloader
{
    public static function register()
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    public static function autoload($class)
    {
        if (str_starts_with($class, __NAMESPACE__ . '\\')) {
            $class = str_replace(__NAMESPACE__ . '\\', '', $class);
            $class = str_replace('\\', '/', $class);
            require __DIR__ . '/' . $class .  '.php';
        }
    }
}
