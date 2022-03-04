<?php

namespace Core\Service;

use Core\Security\Security;
use JetBrains\PhpStorm\Pure;

class Post
{
    #[Pure] public static function get($key): ?string
    {
        return isset($_POST[$key]) ? Security::checkInput($_POST[$key]) : null;
    }

    #[Pure] public static function getAll(): ?array
    {
        return !empty($_POST) ? Security::checkInputs($_POST) : null;
    }
}
