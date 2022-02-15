<?php

namespace App\Controller;

use App\Entity\UserEntity;
use App\Manager\SecurityManager;
use App\Model\PasswordMail;
use App\Model\UserMail;
use Core\Security\CsrfToken;
use Core\Security\Security;
use Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class SecurityController extends AppController
{
    /**
     * Demande la page de connexion
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     * @throws Exception
     */
    public function login($messages = [])
    {
        // Initialisation du formulaire
        $form = $this->createForm('authenticate', true, $messages);

        // Affichage de la vue
        $this->render('security/login.twig', [
            'form' => $form
        ]);
    }

    /**
     * Demande de connexion
     * @throws Exception
     */
    public function authenticate()
    {
        // Nettoyage des données postées
        $formData = Security::checkInputs($_POST);

        // Récupération du token
        $token = new CsrfToken('authenticate', $formData['csrfToken']);
        unset($formData['csrfToken']);

        // Initialisation du formulaire avec données nettoyées
        $form = $this
            ->createForm('authenticate', false)
            ->hydrate($formData);

        if ($form->isTokenValid($token)) {
            try {
                // Création de l'utilisateur
                $user = new UserEntity();
                $user->hydrate($formData);
                $this->userManager->login($user);
            } catch (Exception $e) {
                $form->setError($e->getMessage());
            }

            // TODO : vers l'espace d'administration
            if (!$form->getError()) {
                header('Location: ' . R_BLOG);
            }
        } else {
            throw new Exception('Invalid CSRF token');
        }

        // Affichage de la vue
        $this->render('security/login.twig', [
            'form' => $form
        ]);
    }

    /**
     * Demande la page d'inscription
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    public function signin()
    {
        // Initialisation du formulaire
        $form = $this->createForm('register');

        // Affichage de la vue
        $this->render('security/register.twig', [
            'form' => $form
        ]);
    }

    /**
     * Demande d'inscription
     * @throws Exception
     */
    public function register()
    {
        // Nettoyage des données postées
        $formData = Security::checkInputs($_POST);

        // Récupération du token
        $token = new CsrfToken('register', $formData['csrfToken']);
        unset($formData['csrfToken']);

        // Initialisation du formulaire avec données nettoyées
        $form = $this
            ->createForm('register', false)
            ->hydrate($formData);

        if ($form->isTokenValid($token)) {
            try {
                // Création de l'utilisateur
                $user = new UserEntity();
                $user->hydrate($formData);
                $this->userManager->register($user);
            } catch (Exception $e) {
                $form->setError($e->getMessage());
            }

            if (!$form->getError()) {
                // Envoi du mail
                $email = new UserMail();
                $send_email = $email->sendEmail($user);

                if ($send_email) {
                    // Message de réussite
                    $form->setSuccess(USER_SUCCESS_REGISTRATION);
                } else {
                    // Message d'erreur
                    $form->setError(ERROR_SEND_EMAIL);
                }
            }
        } else {
            throw new Exception('Invalid CSRF token');
        }

        // Affichage de la vue
        $this->render('security/register.twig', [
            'form' => $form
        ]);
    }

    /**
     * Demande de déconnexion
     */
    public function logout()
    {
        $this->userManager->destroyUserSession();
        header('Location: ' . R_HOMEPAGE);
    }

    public function validate(string $email, string $tokenValidation)
    {
        $user = $this->userManager->findUserByEmail($email);

        if ($isTokenValid = SecurityManager::isTokenValid($user, $tokenValidation)) {
            $isUserConfirm = $this->userManager->confirmUser($user);
        }

        if (!$isTokenValid) {
            $message = ['error' => USER_TOKEN_INVALID];
        } elseif (!$isUserConfirm) {
            $message = ['error' => USER_LINK_INVALID];
        } else {
            $message = ['success' => USER_ACCOUNT_ACTIVATED];
        }

        $this->login($message);
    }

    public function forgotPassword()
    {
        // Initialisation du formulaire
        $form = $this->createForm('forgot-password');

        // Affichage de la vue
        $this->render('security/email.twig', [
            'form' => $form
        ]);
    }

    public function emailPassword()
    {
        // Nettoyage des données postées
        $formData = Security::checkInputs($_POST);

        // Récupération du token
        $token = new CsrfToken('forgot-password', $formData['csrfToken']);
        unset($formData['csrfToken']);

        // Initialisation du formulaire avec données nettoyées
        $form = $this
            ->createForm('forgot-password', false)
            ->hydrate($formData);

        if ($form->isTokenValid($token)) {
            try {
                // Création de l'utilisateur
                $user = new UserEntity();
                $user->hydrate($formData);
                if (!($user = $this->userManager->findUserByEmail($user->getEmail()))) {
                    $form->setError(USER_ERROR_NOT_EXISTS);
                }
            } catch (Exception $e) {
                $form->setError($e->getMessage());
            }

            if (!$form->getError()) {
                // Envoi du mail
                $email = new PasswordMail();
                $send_email = $email->sendEmail($user);
                if ($send_email) {
                    // Message de réussite
                    $form->setSuccess(USER_SEND_PASSWORD_EMAIL . $user->getEmail());
                } else {
                    // Message d'erreur
                    $form->setError(ERROR_SEND_EMAIL);
                }
            }
        } else {
            throw new Exception('Invalid CSRF token');
        }

        // Affichage de la vue
        $this->render('security/email.twig', [
            'form' => $form
        ]);
    }

    public function newPassword(string $email, string $tokenValidation)
    {
        $user = $this->userManager->findUserByEmail($email);

        if (!SecurityManager::isTokenValid($user, $tokenValidation)) {
            $this->login(['error' => USER_PASSWORD_TOKEN_INVALID]);
        }

        // Initialisation du formulaire
        $form = $this->createForm('reset-password');

        // Affichage de la vue
        $this->render('security/password.twig', [
            'form' => $form,
            'user' => $user
        ]);
    }

    public function resetPassword()
    {
        // Nettoyage des données postées
        $formData = Security::checkInputs($_POST);

        // Récupération du token
        $token = new CsrfToken('reset-password', $formData['csrfToken']);
        unset($formData['csrfToken']);

        // Initialisation du formulaire avec données nettoyées
        $form = $this
            ->createForm('reset-password', false)
            ->hydrate($formData);

        if ($form->isTokenValid($token)) {
            try {
                // Création de l'utilisateur
                $user = new UserEntity();
                $user->hydrate($formData);
                $this->userManager->resetPassword($user, $formData['validationToken']);
            } catch (Exception $e) {
                $form->setError($e->getMessage());
            }

            if (!$form->getError()) {
                $this->login(['success' => 'Votre mot de passe a été réinitialisé. Vous pouvez désormais vous connecter avec votre nouveau mot de passe.']);
            }
        } else {
            throw new Exception('Invalid CSRF token');
        }

        // Affichage de la vue
        $this->render('security/password.twig', [
            'form' => $form
        ]);
    }
}
