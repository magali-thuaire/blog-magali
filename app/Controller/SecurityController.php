<?php

namespace App\Controller;

use App\Entity\UserEntity;
use App\Service\PasswordMailService;
use App\Service\UserMailService;
use App\Security\Security;
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
        $form = $this->initForm('authenticate', true, $messages);

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
        $form = $this->createForm('authenticate');

        try {
            // Création de l'utilisateur
            $user = new UserEntity();
            $user->hydrate((array) $form);
            $this->userManager->login($user);
        } catch (Exception $e) {
            $form->setError($e->getMessage());
        }

        if (!$form->getError()) {
            header('Location: ' . R_ADMIN);
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
        $form = $this->initForm('register');

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
        $form = $this->createForm('register');

        try {
            // Création de l'utilisateur
            $user = new UserEntity();
            $user->hydrate((array) $form);
            $this->userManager->register($user);
        } catch (Exception $e) {
            $form->setError($e->getMessage());
        }

        if (!$form->getError()) {
            if (UserMailService::send($user)) {
                // Message de réussite
                $form->setSuccess(USER_SUCCESS_REGISTRATION);
            } else {
                // Message d'erreur
                $form->setError(ERROR_SEND_EMAIL);
            }
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
        $this->userManager->logout();
        header('Location: ' . R_HOMEPAGE);
    }

    /**
     * Validation du compte par l'utilisateur
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function validate(string $email, string $token)
    {
        $user = $this->userManager->findUserByEmail($email);

        if ($isTokenValid = Security::isTokenValid($user, $token)) {
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

    /**
     * Demande la page de réinitialisation de mot de passe
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function forgotPassword()
    {
        // Initialisation du formulaire
        $form = $this->initForm('forgot-password');

        // Affichage de la vue
        $this->render('security/email.twig', [
            'form' => $form
        ]);
    }

    /**
     * Demande de réinitialisation de mot de passe
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function emailPassword()
    {
        $form = $this->createForm('forgot-password');

        try {
            // Création de l'utilisateur
            $user = new UserEntity();
            $user->hydrate((array) $form);
            if (!($user = $this->userManager->findUserByEmail($user->getEmail()))) {
                $form->setError(USER_ERROR_NOT_EXISTS);
            }
        } catch (Exception $e) {
            $form->setError($e->getMessage());
        }

        if (!$form->getError()) {
            // Envoi du mail
            if (PasswordMailService::send($user)) {
                // Message de réussite
                $form->setSuccess(USER_SEND_PASSWORD_EMAIL . $user->getEmail());
            } else {
                // Message d'erreur
                $form->setError(ERROR_SEND_EMAIL);
            }
        }

        // Affichage de la vue
        $this->render('security/email.twig', [
            'form' => $form
        ]);
    }

    /**
     * Demande la page de modification de mot de passe
     *
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    public function newPassword(string $email, string $token)
    {
        // Initialisation du formulaire
        $form = $this->initForm('reset-password');

        try {
            $user = $this->userManager->findUserByEmail($email);
        } catch (Exception $e) {
            $form->setError($e->getMessage());
        }

        if ($form->getError()) {
            $this->login(['error' => $form->getError()]);
        }

        // Affichage de la vue
        $this->render('security/password.twig', [
            'form' => $form,
            'user' => $user,
            'token' => $token
        ]);
    }

    /**
     * Demande de modification de mot de passe
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function resetPassword()
    {
        $form = $this->createForm('reset-password');

        try {
            // Création de l'utilisateur
            $user = new UserEntity();
            $user->hydrate((array) $form);
            $this->userManager->resetPassword($user, $form->token);
        } catch (Exception $e) {
            $form->setError($e->getMessage());
        }

        if (!$form->getError()) {
            $this->login(['success' => USER_PASSWORD_CHANGED]);
        }

        // Affichage de la vue
        $this->render('security/password.twig', [
            'form' => $form,
            'user' => $user,
            'token' => $form->token
        ]);
    }
}
