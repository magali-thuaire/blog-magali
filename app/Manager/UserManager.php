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
		$user = $this->findUserByLogin($userData);

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

		$this->createUserSession($user);

		return true;

	}

	public function register(UserEntity $userData)
	{
		// Vérification si l'utilisateur existe déjà
		if($this->isUserExists($userData)) {
			throw new Exception(USER_ERROR_EXISTS);
		}

		// Vérification du mot de passe et de sa confirmation
		if(!$this->isPasswordConfirm($userData)) {
			throw new Exception(USER_ERROR_PASSWORD_CONFIRM);
		}

		// Création de l'utilisateur
		if(!($userId = $this->createUser($userData))) {
			throw new Exception(USER_ERROR_EXISTS);
		}

		return true;

	}

	private function findUserByLogin(UserEntity $user): bool|UserEntity
	{
		$statement = $this->getUserByEmail()->getQuery();
		$user = $this->prepare($statement, [':email' => $user->getEmail()], true, true);
		return $user;
	}

	private function getUserByEmail(): QueryBuilder
	{
		$qb = new QueryBuilder();
		return $qb
				->select('u.id', 'u.username', 'u.email', 'u.password', 'u.user_confirmed as userConfirmed', 'u.admin_validated as adminValidated')
				->from('user', 'u')
				->where('u.email = :email')
		;
	}

	//TODO: cryptage du mot de passe
	private function isPasswordValid($user, $plainPassword): bool
	{
		return $plainPassword === $user->getPassword();
	}

	private function createUserSession(UserEntity $user) {
		$_SESSION['user'] = $user;
	}

	// TODO: nombre de caractère dans le mot de passe
	private function isPasswordConfirm(UserEntity $user): bool
	{

		// Vérification du mot de passe
		if(!is_string($user->plainPassword) || empty($user->plainPassword)) {
			throw new Exception(USER_ERROR_PASSWORD);
		}

		// Vérification de la vérification du mot de passe
		if(!is_string($user->plainPasswordConfirm) || empty($user->plainPasswordConfirm)) {
			throw new Exception(USER_ERROR_PASSWORD);
		}

		return $user->plainPassword === $user->plainPasswordConfirm;
	}

	private function isUserExists(UserEntity$user) {
		$statement = $this->getUserByEmail()->getQuery();
		return $this->execute($statement, [':email' => $user->getEmail()]);
	}

	private function createUser(UserEntity $user): bool {

		$user
			->setPassword($user->plainPassword)
			->setValidationToken()
			->setRole(null)
		;

		$statement = $this->newUser()->getQuery();
		$attributs = [
			':username' 		=> $user->getUsername(),
			':email'			=> $user->getEmail(),
			':password'			=> $user->getPassword(),
			':validation_token'	=> $user->getValidationToken(),
			':role'				=> $user->getRole()
		];

		return $this->execute($statement, $attributs);
	}

	private function newUser() {
		$qb = new QueryBuilder();
		return $qb
				->insert('user')
				->values('username', 'email', 'password', 'validation_token', 'role')
		;
	}
}