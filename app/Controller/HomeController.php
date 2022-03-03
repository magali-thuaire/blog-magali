<?php

namespace App\Controller;

use App\App;
use App\Entity\ContactEntity;
use App\Service\ContactMailService;
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
        $form = $this->initForm('contact');

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
        $form = $this->createForm('contact');

        try {
            // Création du contact
            $contact = new ContactEntity();
            $contact->hydrate((array) $form);
        } catch (Exception $e) {
            $form->setError($e->getMessage());
        }

        if (!$form->hasError()) {
            // Envoi du mail
            if (ContactMailService::send($contact)) {
                // Enregistrement en BDD
                $this->contactManager->new($contact);
                $form->setSuccess(App::$config['CONTACT_SUCCESS_EMAIL']);
            } else {
                // Message d'erreur
                $form->setError(App::$config['ERROR_SEND_EMAIL']);
            }
        }

        // Données du formulaire en json
        echo json_encode(['form' => $form]);
    }
}
