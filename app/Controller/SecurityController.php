<?php

namespace App\Controller;

use App\Entity\UserEntity;
use App\Manager\UserManager;
use Core\Model\FormModel;
use Core\Security\CsrfToken;
use Core\Security\Security;
use Exception;

class SecurityController extends AppController
{

	private UserManager $userManager;

	public function __construct()
	{
		$this->userManager = $this->getManager('user');
	}

	public function login()
	{
		// Initialisation du formulaire
		$form = $this->initLoginForm();

		// Affichage de la vue
		$this->render('security.login', [
			'form' => $form
		]);
	}

	public function authenticate()
	{
		// Nettoyage des données postées
		$formData = Security::checkInputs($_POST);

		// Récupération du token
		$token = new CsrfToken('authenticate', $formData['csrfToken']);
		unset($formData['csrfToken']);

		// Initialisation du formulaire avec données nettoyées
		$form = $this
			->initLoginForm()
			->hydrate($formData);

		if($form->isTokenValid($token)) {

			try {
				// Création de l'utilisateur
				$user = new UserEntity();
				$user->hydrate($formData);
				$this->userManager->login($user);
			} catch (Exception $e) {
				$form->setError($e->getMessage());
			}

			if(!$form->getError()) {
				header('Location: ' . R_BLOG);
			} else {

			}

		} else {
			throw new Exception('Invalid CSRF token');
		}

		// Affichage de la vue
		$this->render('security.login', [
			'form' => $form
		]);
	}

	private function initLoginForm()
	{
		return new FormModel('authenticate');
	}
}