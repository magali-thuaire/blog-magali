<?php

namespace App\Controller;

use App\Model\FormContact;
use Core\Security;

class HomeController extends AppController
{
	public function index()
	{
		$this->render('homepage.index');
	}

	public function newContact()
	{
		$formData = Security::checkInputs($_POST);
		$formContact = new FormContact();

		try {
			$formContact->hydrate($formData);
		} catch (\Exception $e) {
			$formContact->setError($e->getMessage());
		}

		if($formContact->getError()) {
			// TODO: message d'erreur
		} else {
			// TODO: enregistrement en BDD
			// TODO: envoi du mail
			// TODO: message de réussite
		}

		$this->render('homepage.index');

	}

}