<?php

namespace App\Controller;

use App;
use App\Entity\ContactEntity;
use App\Manager\ContactManager;
use App\Model\ContactMail;
use Core\Model\FormModel;
use Core\Security\CsrfToken;
use Core\Security\Security;
use Exception;

class HomeController extends AppController
{

	/** @var ContactManager */
	private $contactManager;

	public function __construct()
	{
		$this->contactManager = $this->getManager('contact');
	}

	// Demande de la page d'accueil
	public function index()
	{
		// Initialisation du formulaire
		$form = $this->initContactForm();

		// Affichage de la vue
		$this->render('homepage.index', [
			'form' => $form
		]);

	}

	// Validation du formulaire depuis appel AJAX
	public function newContact()
	{
		// Nettoyage des données postées
		$formData = Security::checkInputs($_POST);

		// Récupération du token
		$token = new CsrfToken('contact', $formData['csrfToken']);
		unset($formData['csrfToken']);

		// Initialisation du formulaire avec données nettoyées
		$form = $this
			->initContactForm()
			->hydrate($formData);

		if($form->isTokenValid($token)) {

			try {
				// Création du contact
				$contact = new ContactEntity();
				$contact->hydrate($formData);
			} catch (Exception $e) {
				$form->setError($e->getMessage());
			}

			if(!$form->getError()) {

				// Envoi du mail
				$email = new ContactMail();
				$send_email = $email->sendEmail($contact);

				if ($send_email) {
					// Enregistrement en BDD
					$this->contactManager->new($contact);
					// Message de réussite
					$form->setSuccess(CONTACT_SUCCESS_MESSAGE);
				} else {
					// Message d'erreur
					$form->setError(ERROR_SEND_SEMAIL);
				}
			}

		} else {
			throw new Exception('Invalid CSRF token');
		}

		// Données du formulaire en json
		echo json_encode(['form' => $form]);

	}

	private function initContactForm()
	{
		return new FormModel('contact');
	}

}