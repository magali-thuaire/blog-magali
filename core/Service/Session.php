<?php

namespace Core\Service;

class Session
{
    public static function put($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key)
    {
        return ($_SESSION[$key] ?? null);
    }

    public static function getAll(): ?array
    {
        return ($_SESSION ?? null);
    }

    public static function unset($key)
    {
        unset($_SESSION[$key]);
    }
}
