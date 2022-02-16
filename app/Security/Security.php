<?php

namespace App\Security;

use App\Controller\SecurityController;
use App\Entity\UserEntity;
use Core\Security\Security as CoreSecurity;
use JetBrains\PhpStorm\Pure;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

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

    public static function isAccessGranted(): bool
    {

        $user = self::getUser();
        if (in_array($user->getRole(), ['ROLE_SUPERADMIN', 'ROLE_ADMIN'])) {
            return true;
        }

        return false;
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public static function getUser()
    {
        // Récupération de l'utilisateur
        if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
            return $_SESSION['user'];
        } else {
            $controller = new SecurityController();
            $controller->login();
        }
    }
}
