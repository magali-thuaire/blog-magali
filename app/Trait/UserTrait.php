<?php

namespace App\Trait;

use App\Entity\UserEntity;
use App\Security\Security;
use Exception;

trait UserTrait
{
    /**
     * Déconnecte l'utilisateur
     */
    public function logout(): void
    {
        unset($_SESSION['user']);
    }

    /**
     * Création de l'utilisateur en session
     * @param UserEntity $user
     */
    private function createUserSession(UserEntity $user): void
    {
        $_SESSION['user'] = $user;
    }

    /**
     * Vérifie que le mot de passe est valide
     * @param $user
     * @param $plainPassword
     *
     * @return bool
     */
    private function isPasswordValid($user, $plainPassword): bool
    {
        return password_verify($plainPassword, $user->getPassword());
    }

    /**
     * Vérifie que le mot de passe et sa confirmation sont identiques
     * @throws Exception
     */
    private function isPasswordConfirm(UserEntity $user): bool
    {

        // Vérification du mot de passe
        if (!is_string($user->plainPassword) || empty($user->plainPassword)) {
            throw new Exception(USER_ERROR_PASSWORD);
        }

        // Vérification de la vérification du mot de passe
        if (!is_string($user->plainPasswordConfirm) || empty($user->plainPasswordConfirm)) {
            throw new Exception(USER_ERROR_PASSWORD);
        }

        return $user->plainPassword === $user->plainPasswordConfirm;
    }

    /**
     * @throws Exception
     */
    private function isTokenValid($user, $token): bool
    {
        if (!Security::isTokenValid($user, $token)) {
            throw new Exception(USER_PASSWORD_TOKEN_INVALID);
        }

        return true;
    }
}
