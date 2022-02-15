<?php

namespace App\Manager;

use App\Entity\UserEntity;
use Core\Manager\EntityManager;
use JetBrains\PhpStorm\Pure;

class SecurityManager extends EntityManager
{
    #[Pure] public static function isTokenValid(UserEntity $user, string $tokenValidation): bool
    {
        return $user->getValidationToken() === $tokenValidation;
    }

    #[Pure] public static function generateValidationLink(UserEntity $user): string
    {
        return URL_SITE .
               '/index.php?p=validate&email=' . $user->getEmail() . '&tokenValidation=' . $user->getValidationToken();
    }
}
