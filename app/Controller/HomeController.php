<?php

namespace App\Controller;

use App;
use App\Entity\Contact;
use Core\Model\FormModel;
use Core\Security\CsrfToken;
use Core\Security\Security;

class HomeController extends AppController
{
	// Demande de la page d'accueil
	public function index()
	{
		// Initialisation du formulaire
		$form = $this->initFormContact();

		// Affichage de la vue
		$this->render('homepage.index', $form);

	}

	// Validation du formulaire depuis appel AJAX
	public function newContact()
	{
		// Initialisation du formulaire
		$form = $this->initFormContact();

		// Nettoyage des données postées
		$formData = Security::checkInputs($_POST);

		// Récupération du token
		$token = new CsrfToken('contact', $formData['csrfToken']);
		unset($formData['csrfToken']);

		// Création du contact
		$contact = new Contact();

		if($form->isTokenValid($token)) {
			try {
				$contact->hydrate($formData);
			} catch (\Exception $e) {
				$form->setError($e->getMessage());
			}

			// Si erreur
			if($form->getError()) {
				// Récupération des données du formulaire et des erreurs
				$form->hydrate($formData);
				// Si succès
			} else {
				$form->setSuccess(CONTACT_SUCCESS_MESSAGE);
				// TODO: enregistrement en BDD
				// TODO: envoi du mail
			}
		} else {
			throw new \Exception('Invalid CSRF token');
		}

		// Données du formulaire en json
		echo json_encode(['form' => $form]);

	}

	private function initFormContact()
	{
		return new FormModel('contact');
	}

}