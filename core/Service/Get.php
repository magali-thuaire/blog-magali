<?php

namespace Core\Service;

use Core\Security\Security;
use JetBrains\PhpStorm\Pure;

class Get
{
    #[Pure] public static function get($key): ?string
    {
        return isset($_GET[$key]) ? Security::checkInput($_GET[$key]) : null;
    }
}
