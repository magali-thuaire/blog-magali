<?php

namespace App\Controller;

use App\App;
use App\Security\Security;
use Core\Manager\EntityManager;
use Core\Model\FormModel;
use Core\Security\CsrfToken;
use Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AppController
{
    /**
     * Retourne le manager de l'entité demandée
     * @param string $entity
     *
     * @return EntityManager
     */
    protected function getManager(string $entity): EntityManager
    {
        return App::getInstance()->getManager($entity);
    }

    /**
     * Retourne la vue twig demandée
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    protected function render($view, $variables = [])
    {
        return App::getInstance()->getRenderer()->render($view, $variables);
    }

    /**
     * Retourne un nouveau formulaire avec l'initialisation d'un token csrf
     * @throws Exception
     */
    protected function initForm(string $tokenKey, bool $tokenInitialize = true, array $messages = []): FormModel
    {
        if ($tokenInitialize) {
            $_SESSION[$tokenKey] = uniqid(rand(), true);
        }
        return new FormModel($tokenKey, $messages);
    }

    /**
     * Retourne une nouveau formulaire posté avec vérification du token csrf
     *
     * @param string $tokenKey
     *
     * @return FormModel
     * @throws Exception
     */
    protected function createForm(string $tokenKey): FormModel
    {
        // Nettoyage des données postées
        $formData = Security::checkInputs($_POST);

        // Récupération du token
        $token = new CsrfToken($tokenKey, $formData['csrfToken']);
        unset($formData['csrfToken']);

        // Initialisation du formulaire avec données nettoyées
        $form = $this
            ->initForm($tokenKey, false)
            ->hydrate($formData);

        if (!$form->isTokenValid($token)) {
            throw new Exception(INVALID_CSRF_TOKEN);
        }

        return $form;
    }

    /**
     * Retourne le manager demandé
     * @param string $key
     *
     * @return EntityManager|null
     */
    public function __get(string $key): ?EntityManager
    {
        if (!str_contains($key, 'Manager')) {
            return null;
        }
        $entity = str_replace('Manager', '', $key);
        return $this->getManager($entity);
    }

    protected function getUser()
    {
        return Security::getUser();
    }
}
