<?php

namespace App\Controller;

use App;
use App\Entity\ContactEntity;
use App\Manager\ContactManager;
use App\Model\AppMail;
use Core\Model\FormModel;
use Core\Security\CsrfToken;
use Core\Security\Security;
use Exception;

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
		// Nettoyage des données postées
		$formData = Security::checkInputs($_POST);

		// Récupération du token
		$token = new CsrfToken('contact', $formData['csrfToken']);
		unset($formData['csrfToken']);

		// Initialisation du formulaire avec données nettoyées
		$form = $this
			->initFormContact()
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
				$email = new AppMail();
				$send_email = $email->sendEmail($contact);

				if ($send_email) {

					// Enregistrement en BDD
					$app = App::getInstance();
					/** @var ContactManager $em */
					$em = $app->getManager('contact');
					$em->new($contact);

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

	private function initFormContact()
	{
		return new FormModel('contact');
	}

}