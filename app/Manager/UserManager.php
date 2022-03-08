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

    /**
     * Mise à jour d'un utilisateur
     *
     * @param UserEntity $user
     * @param bool       $isAdminValidated
     *
     * @return bool
     */
    public function update(UserEntity $user, bool $isAdminValidatedChange): bool
    {
        $qb = $this->createQueryBuilder()
                   ->update('user', 'u')
                   ->set('u.role = :role')
                   ->where('u.id = :id')
        ;

        $attributs = [
            ':id'               => $user->getId(),
            ':role'             => $user->getRole(),
        ];

        if ($isAdminValidatedChange) {
            $qb->addSet('u.admin_validated = :admin_validated');
            $adminValidated = [':admin_validated'  => $user->isAdminValidated()];
            if (!$user->isAdminValidated()) {
                $qb->addSet('u.role = :role');
                $adminValidated = array_merge($adminValidated, [':role' => UserEntity::ROLE_USER]);
            }

            $attributs = array_merge($attributs, $adminValidated);
        }

        $statement = $qb->getQuery();

        return $this->execute($statement, $attributs);
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
    public function getOneByEmail(): QueryBuilder
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
    public function createUserQuery(): QueryBuilder
    {
        return $this->createQueryBuilder()
                ->insert('user')
                ->columns('username', 'email', 'password', 'validation_token', 'role')
        ;
    }
}
