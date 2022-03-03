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
     * Retourne un tableau de User
     * @throws Exception
     */
    private function createUsers($usersData): ?array
    {
        $users = [];
        if ($usersData) {
            foreach ($usersData as $userData) {
                $users[] = $this->createUser($userData);
            }
        }
        return $users;
    }

    /**
     * Retourne un objet User
     */
    private function createUser($data): UserEntity
    {
        // Création de l'utilisateur
        $userData = [
            'id'                => $data->id,
            'username'          => $data->username,
            'email'             => $data->email,
            'role'              => $data->role,
            'userConfirmed'     => $data->userConfirmed,
            'adminValidated'    => $data->adminValidated,
            'createdAt'         => $data->createdAt,
        ];


        $user = new UserEntity();
        $user->hydrate($userData);

        return $user;
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
