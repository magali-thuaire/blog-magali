<?php

namespace App\Manager;

use App\App;
use App\Entity\UserEntity;
use App\Trait\UserTrait;
use Core\Database\DatabaseInterface;
use Core\Manager\EntityManager;
use Exception;

class SecurityManager extends EntityManager
{
    use UserTrait;

    private UserManager $userManager;

    public function __construct(DatabaseInterface $db)
    {
        parent ::__construct($db);
        $this->userManager = new UserManager($db);
    }

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

        $user = $this->userManager->findOneByEmail($userData->getEmail());

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
            $user = $this->userManager->findOneByEmail($userData->getEmail());

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
     * Vérifie que l'utilisateur identifié par son email existe en base de données
     * @param UserEntity $user
     *
     * @return bool
     */
    private function isUserExists(UserEntity $user): bool
    {
        $statement = $this->userManager->getOneByEmail()->getQuery();
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

        $statement = $this->userManager->createUserQuery()->getQuery();
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
}
