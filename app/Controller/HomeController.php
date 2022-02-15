<?php

namespace App\Controller;

use App;
use App\Entity\ContactEntity;
use App\Model\ContactMail;
use Core\Security\CsrfToken;
use Core\Security\Security;
use Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class HomeController extends AppController
{
    /**
     * Demande de la page d'accueil
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    public function index()
    {
        // Initialisation du formulaire
        $form = $this->createForm('contact');

        // Affichage de la vue
        return $this->render('homepage/index.twig', [
            'form' => $form
        ]);
    }

    /**
     * Validation du formulaire depuis appel AJAX
     * @throws Exception
     */
    public function newContact()
    {
        // Nettoyage des données postées
        $formData = Security::checkInputs($_POST);

        // Récupération du token
        $token = new CsrfToken('contact', $formData['csrfToken']);
        unset($formData['csrfToken']);

        // Initialisation du formulaire avec données nettoyées
        $form = $this
            ->createForm('contact', false)
            ->hydrate($formData);

        if ($form->isTokenValid($token)) {
            try {
                // Création du contact
                $contact = new ContactEntity();
                $contact->hydrate($formData);
            } catch (Exception $e) {
                $form->setError($e->getMessage());
            }

            if (!$form->getError()) {
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
                    $form->setError(ERROR_SEND_EMAIL);
                }
            }
        } else {
            throw new Exception('Invalid CSRF token');
        }

        // Données du formulaire en json
        echo json_encode(['form' => $form]);
    }
}
