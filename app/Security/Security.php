<?php

namespace App\Security;

use App\Entity\UserEntity;
use Core\Security\Security as CoreSecurity;
use JetBrains\PhpStorm\Pure;

class Security extends CoreSecurity
{
    #[Pure] public static function isTokenValid(UserEntity $user, string $token): bool
    {
        return $user->getValidationToken() === $token;
    }

    #[Pure] public static function generateValidationLink(UserEntity $user): string
    {
        return URL_SITE .
               '/index.php?p=validate&email=' . $user->getEmail() . '&token=' . $user->getValidationToken();
    }

    #[Pure] public static function generateResetPasswordLink(UserEntity $user): string
    {
        return URL_SITE .
               '/index.php?p=reset-password&email=' . $user->getEmail() . '&token=' . $user->getValidationToken();
    }
}
