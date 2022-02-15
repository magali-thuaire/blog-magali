<?php

namespace App\Manager;

use App\Entity\UserEntity;
use Core\Database\QueryBuilder;
use Core\Manager\EntityManager;
use Exception;

class UserManager extends EntityManager
{
    /**
     * @throws Exception
     */
    public function login(UserEntity $userData): bool
    {
        $user = $this->findUserByLogin($userData);

        // Vérification utilisateur existant
        if (!$user) {
            throw new Exception(INVALID_CREDENTIALS);
        }

        // Vérification utilisateur validé et confirmé par admin
        if (!($user->isUserValidated())) {
            throw new Exception(USER_ERROR_VALIDATION);
        }

        // Vérification du mot de passe
        if (!$this->isPasswordValid($user, $userData->plainPassword)) {
            throw new Exception(INVALID_CREDENTIALS);
        }
        $this->createUserSession($user);

        return true;
    }

    /**
     * @throws Exception
     */
    public function register(UserEntity $userData): bool
    {
        // Vérification si l'utilisateur existe déjà
        if ($this->isUserExists($userData)) {
            throw new Exception(USER_ERROR_EXISTS);
        }

        // Vérification du mot de passe et de sa confirmation
        if (!$this->isPasswordConfirm($userData)) {
            throw new Exception(USER_ERROR_PASSWORD_CONFIRM);
        }

        // Création de l'utilisateur
        if (!($this->new($userData))) {
            throw new Exception(USER_ERROR_EXISTS);
        }

        return true;
    }

    private function findUserByLogin(UserEntity $user): bool|UserEntity
    {
        $statement = $this->getUserByEmail()->getQuery();
        return $this->prepare($statement, [':email' => $user ->getEmail()], true, true);
    }

    private function getUserByEmail(): QueryBuilder
    {
        return $this->createQueryBuilder()
                ->select('u.id', 'u.username', 'u.email', 'u.password', 'u.role')
                ->addSelect('u.user_confirmed as userConfirmed', 'u.admin_validated as adminValidated')
                ->from('user', 'u')
                ->where('u.email = :email')
        ;
    }

    private function isPasswordValid($user, $plainPassword): bool
    {
        return password_verify($plainPassword, $user->getPassword());
    }

    private function createUserSession(UserEntity $user): void
    {
        $_SESSION['user'] = $user;
    }

    public function destroyUserSession(): void
    {
        unset($_SESSION['user']);
    }

    // TODO: nombre de caractère dans le mot de passe

    /**
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

    private function isUserExists(UserEntity $user): int|string
    {
        $statement = $this->getUserByEmail()->getQuery();
        return $this->execute($statement, [':email' => $user->getEmail()]);
    }

    /**
     * @throws Exception
     */
    private function new(UserEntity $user): bool
    {
        $user
            ->setPassword($user->plainPassword)
            ->setValidationToken()
            ->setRole(null)
        ;

        $statement = $this->createUser()->getQuery();
        $attributs = [
            ':username'         => $user->getUsername(),
            ':email'            => $user->getEmail(),
            ':password'         => $user->getPassword(),
            ':validation_token' => $user->getValidationToken(),
            ':role'             => $user->getRole()
        ];

        return $this->execute($statement, $attributs);
    }

    private function createUser(): QueryBuilder
    {
        return $this->createQueryBuilder()
                ->insert('user')
                ->values('username', 'email', 'password', 'validation_token', 'role')
        ;
    }
}
