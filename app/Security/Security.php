<?php

namespace App\Security;

use App\App;
use App\Entity\UserEntity;
use Core\Security\Security as CoreSecurity;
use Core\Service\Session;
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

    public static function isAccessGranted(): bool
    {
        $user = self::getUser();

        if (!in_array($user->getRole(), [UserEntity::ROLE_SUPERADMIN, UserEntity::ROLE_ADMIN])) {
            return false;
        }

        return true;
    }

    public static function isSuperAdmin(): bool
    {
        $user = self::getUser();

        if ($user->getRole() !== UserEntity::ROLE_SUPERADMIN) {
            return false;
        }

        return true;
    }

    /**
     */
    #[Pure] public static function getUser()
    {
        // Récupération de l'utilisateur
        if (!empty(Session::get('user'))) {
            return Session::get('user');
        }
    }
}
