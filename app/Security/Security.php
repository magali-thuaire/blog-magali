<?php

namespace App\Security;

use App\App;
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
        return App::$config['URL_SITE'] .
               '/validate/' . $user->getEmail() . '/' . $user->getValidationToken();
    }

    #[Pure] public static function generateResetPasswordLink(UserEntity $user): string
    {
        return App::$config['URL_SITE'] .
               '/reset-password/' . $user->getEmail() . '/' . $user->getValidationToken();
    }

    public static function getUser()
    {
        // Récupération de l'utilisateur
        if (!empty($user = App::request()->get('session', 'user'))) {
            return $user;
        }
    }
}
