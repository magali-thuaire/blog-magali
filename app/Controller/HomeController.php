<?php

namespace App\Controller;

use App\Model\FormContact;
use Core\Model\FormModel;
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

		// Validation du formulaire
		try {
			$formContact->hydrate($formData);
		} catch (\Exception $e) {
			$formContact->setError($e->getMessage());
		}

		$form = new FormModel();

		// Si erreur
		if($formContact->getError()) {
			// Récupération des données du formulaire et des erreurs
			$form->hydrate($formData);
			$form->setError($formContact->getError());
		// Si succès
		} else {
			$form->setSuccess(CONTACT_SUCCESS_MESSAGE);
			// TODO: enregistrement en BDD
			// TODO: envoi du mail
		}

		echo json_encode(['form' => $form]);

	}

}