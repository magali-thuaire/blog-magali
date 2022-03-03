<?php

namespace App\Manager;

use App\App;
use App\Entity\UserEntity;
use Core\Database\QueryBuilder;
use Core\Manager\EntityManager;
use Exception;
use App\Trait\UserTrait;

class UserManager extends EntityManager
{
    use UserTrait;

    /**
     * Connecte l'utilisateur
     * @throws Exception
     */
    public function login(UserEntity $userData): void
    {
        // Vérification utilisateur validé et confirmé par admin
        if (!($this->isUserExists($userData))) {
            throw new Exception(App::$config['INVALID_CREDENTIALS']);
        }
        $user = $this->findOneByEmail($userData->getEmail());

        if ($this->canLogin($user, $userData->plainPassword)) {
            $this->createUserSession($user);
        }
    }

    /**
     * Vétifie que l'utilisateur peut se connecter
     *
     * @param UserEntity $user
     * @param string     $plainPassword
     *
     * @return bool
     * @throws Exception
     */
    private function canLogin(UserEntity $user, string $plainPassword): bool
    {

        // Vérification utilisateur validé et confirmé par admin
        if (!($user->isUserValidated())) {
            throw new Exception(App::$config['USER_ERROR_VALIDATION']);
        }

        // Vérification du mot de passe
        if (!$this->isPasswordValid($user, $plainPassword)) {
            throw new Exception(App::$config['INVALID_CREDENTIALS']);
        }

        return true;
    }

    /**
     * Enregistre l'utilisateur
     * @throws Exception
     */
    public function register(UserEntity $user): bool
    {

        if ($this->canRegister($user)) {
            $register = $this->new($user);
        }

        if (!$register) {
            throw new Exception(App::$config['USER_ERROR_EXISTS']);
        } else {
            return true;
        }
    }

    /**
     * Vérifie que l'utilisateur peut s'enregistrer
     * @throws Exception
     */
    private function canRegister(UserEntity $user): bool
    {

        // Vérification si l'utilisateur existe déjà
        if ($this->isUserExists($user)) {
            throw new Exception(App::$config['USER_ERROR_EXISTS']);
        }

        // Vérification du mot de passe et de sa confirmation
        if (!$this->isPasswordConfirm($user)) {
            throw new Exception(App::$config['USER_ERROR_PASSWORD_CONFIRM']);
        }

        return true;
    }

    /**
     * Modifie le mot de passe
     * @throws Exception
     */
    public function resetPassword(UserEntity $userData, string $token): void
    {
        if ($this->canResetPassword($userData)) {
            $user = $this->findOneByEmail($userData->getEmail());

            if ($this->isTokenValid($user, $token)) {
                // Modification du mot de passe
                $this->updatePassword($user, $userData->plainPassword);
            }
        }
    }

    /**
     * Vérifie que l'utilisateur peut modifier son mot de passe
     * @throws Exception
     */
    private function canResetPassword(UserEntity $userData): bool
    {
        // Vérification si l'utilisateur existe
        if (!$this->isUserExists($userData)) {
            throw new Exception(App::$config['USER_ERROR_NOT_EXISTS']);
        }

        // Vérification du mot de passe et de sa confirmation
        if (!$this->isPasswordConfirm($userData)) {
            throw new Exception(App::$config['USER_ERROR_PASSWORD_CONFIRM']);
        }

        return true;
    }


    //--------------------------------------------------------------
    //------- Requêtes SQL
    //--------------------------------------------------------------

    /**
     * Retourne tous les utilisateurs
     *
     */
    public function findAll(): ?array
    {
        $statement = $this->getAll()->getQuery();
        $usersData = $this->query($statement);

        return $this->createUsers($usersData);
    }

    /**
     * Retourne un unique utilisateur en fonction de son id
     * @throws Exception
     */
    public function findOneById(int $id, UserEntity $user): ?UserEntity
    {

        if ($user->getRole() === UserEntity::ROLE_SUPERADMIN) {
            $statement = $this->getOneById()->getQuery();
            $userData = $this->prepare($statement, [':id' => $id], true, false);
        } else {
            throw new Exception(App::$config['ADMIN_USER_ERROR_MESSAGE']);
        }

        if ($userData) {
            return $this->createUser($userData);
        } else {
            return throw new Exception(App::$config['ADMIN_USER_NOT_EXISTS']);
        }
    }

    /**
     * Retourne un unique utilisateur en fonction de son email
     * @throws Exception
     */
    public function findOneByEmail(string $email): ?UserEntity
    {
        $statement = $this->getOneByEmail()->getQuery();
        $user = $this->prepare($statement, [':email' => $email], true, true);

        if ($user) {
            return $user;
        } else {
            return null;
        }
    }

    /**
     * Vérifie que l'utilisateur identifié par son email existe en base de données
     * @param UserEntity $user
     *
     * @return bool
     */
    private function isUserExists(UserEntity $user): bool
    {
        $statement = $this->getOneByEmail()->getQuery();
        $user = $this->execute($statement, [':email' => $user->getEmail()]);

        if ($user) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Création d'un nouvel utilisateur
     * @throws Exception
     */
    private function new(UserEntity $user): bool
    {
        $user
            ->setPassword($user->plainPassword)
            ->setValidationToken()
            ->setRole(null)
        ;

        $statement = $this->createUserQuery()->getQuery();
        $attributs = [
            ':username'         => $user->getUsername(),
            ':email'            => $user->getEmail(),
            ':password'         => $user->getPassword(),
            ':validation_token' => $user->getValidationToken(),
            ':role'             => $user->getRole()
        ];

        return $this->execute($statement, $attributs);
    }

    /**
     * Met à jour le champ permettant de vérifier que l'utilisateur a confirmé son email
     * @param UserEntity $user
     *
     * @return bool
     */
    public function confirmUser(UserEntity $user): bool
    {
        $qb = $this->createQueryBuilder()
            ->update('user', 'u')
            ->set('u.user_confirmed = true')
            ->where('u.id = :id', 'user_confirmed IS false')
        ;

        $statement = $qb->getQuery();
        $attributs = [
            ':id' => $user->getId()
        ];
        return $this->execute($statement, $attributs);
    }

    /**
     * Met à jour le champ mot de passe
     * @throws Exception
     */
    private function updatePassword(UserEntity $user, string $password): bool
    {
        $qb = $this->createQueryBuilder()
                   ->update('user', 'u')
                   ->set('u.password = :password')
                   ->where('u.id = :id')
        ;

        $statement = $qb->getQuery();
        $attributs = [
            ':id'       => $user->getId(),
            ':password' => $user->setPassword($password)->getPassword()
        ];

        $isUpdatedPassword = $this->execute($statement, $attributs);

        if (!$isUpdatedPassword) {
            throw new Exception(App::$config['USER_PASSWORD_MODIF_INVALID']);
        } else {
            return true;
        }
    }

    /**
     * Valider un utilisateur
     *
     * @param int        $id
     * @param UserEntity $user
     *
     * @return bool
     */
    public function validate(int $id, UserEntity $user): bool
    {

        if ($user->getRole() === UserEntity::ROLE_SUPERADMIN) {
            $qb = $this->createQueryBuilder()
                       ->update('user', 'u')
                       ->set('u.admin_validated = TRUE', 'u.role = :role')
                       ->where('u.id = :id')
            ;
            $attributs = [
                ':id' => $id,
                ':role' => UserEntity::ROLE_ADMIN
            ];
            $statement = $qb->getQuery();
            return $this->execute($statement, $attributs);
        } else {
            return false;
        }
    }

    /**
     * Supprimer un utilisateur
     */
    public function delete(UserEntity $user, UserEntity $admin): bool
    {
        if ($admin->getRole() === UserEntity::ROLE_SUPERADMIN) {
            $statement = $this->createQueryBuilder()
                              ->delete('user', 'u', 'u')
                              ->where('u.id = :id')
                              ->getQuery()
            ;
            return $this->execute($statement, [':id' => $user->getId()]);
        } else {
            return false;
        }
    }

    //--------------------------------------------------------------
    //------- Query Builder
    //--------------------------------------------------------------

    /**
     * Retourne le QB de tous les utilisateurs
     * @return QueryBuilder
     */
    private function getAll(): QueryBuilder
    {
        return $this->createQueryBuilder()
                    ->select('u.id', 'u.username', 'u.email', 'u.role')
                    ->addSelect('u.user_confirmed as userConfirmed', 'u.admin_validated as adminValidated')
                    ->addSelect('u.created_at as createdAt')
                    ->from('user', 'u')
        ;
    }

    /**
     * Retourne le QB d'un utilisateur unique identifié par son id
     * @return QueryBuilder
     */
    private function getOneById(): QueryBuilder
    {
        return $this->getAll()
                    ->andWhere('u.id = :id')
        ;
    }

    /**
     * Retourne le QB d'un utilisateur unique identifié par son email
     * @return QueryBuilder
     */
    private function getOneByEmail(): QueryBuilder
    {
        return $this->createQueryBuilder()
                    ->select('u.id', 'u.username', 'u.email', 'u.password', 'u.role')
                    ->addSelect('u.validation_token as validationToken')
                    ->addSelect('u.user_confirmed as userConfirmed', 'u.admin_validated as adminValidated')
                    ->from('user', 'u')
                    ->where('u.email = :email')
            ;
    }

    /**
     * Retourne le QB permettant de créer un utilisateur en base de données
     * @return QueryBuilder
     */
    private function createUserQuery(): QueryBuilder
    {
        return $this->createQueryBuilder()
                ->insert('user')
                ->columns('username', 'email', 'password', 'validation_token', 'role')
        ;
    }
}
