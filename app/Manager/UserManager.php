<?php

namespace App\Manager;

use App\Entity\UserEntity;
use Core\Database\QueryBuilder;
use Core\Manager\EntityManager;
use Exception;

class UserManager extends EntityManager
{
	public function login(UserEntity $userData)
	{
		$user = $this->findUserById($userData);

		// Vérification utilisateur existant
		if(!$user) {
			throw new Exception(INVALID_CREDENTIALS);
		}

		// Vérification utilisateur validé et confirmé par admin
		if(!($user->isUserValidated())) {
			throw new Exception(USER_ERROR_VALIDATION);
		}
		// Vérification du mot de passe
		if(!$this->isPasswordValid($user, $userData->getPassword())) {
			throw new Exception(INVALID_CREDENTIALS);
		};

		$this->createUserInSession($user);

		return true;

	}

	private function findUserById(UserEntity $user): bool|UserEntity
	{
		$statement = $this->getUserByLogin()->getQuery();
		$user = $this->prepare($statement, [':login' => $user->getLogin()], true, true);
		return $user;
	}

	private function getUserByLogin(): QueryBuilder
	{
		$qb = new QueryBuilder();
		return $qb
				->select('u.id', 'u.username', 'u.email', 'u.login', 'u.password', 'u.user_confirmed as userConfirmed', 'u.admin_validated as adminValidated')
				->addSelect('r.name as role')
				->from('user', 'u')
				->innerJoin('role', 'r', 'r.id = u.role')
				->where('u.login = :login')
		;
	}

	//TODO: cryptage du mot de passe
	private function isPasswordValid($user, $plainPassword): bool
	{
		return sha1($plainPassword) === $user->getPassword();
	}

	private function createUserInSession(UserEntity $user) {
		$_SESSION['user'] = $user;
	}
}