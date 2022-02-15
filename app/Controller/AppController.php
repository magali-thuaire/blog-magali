<?php

namespace App\Controller;

use App\App;
use Core\Manager\EntityManager;
use Core\Model\FormModel;
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
     * Retourne un nouveau formulaire avec l'initialisation d'un token
     * @throws Exception
     */
    protected function createForm(string $tokenKey, bool $tokenInitialize = true, array $messages = []): FormModel
    {
        if ($tokenInitialize) {
            $_SESSION[$tokenKey] = uniqid(rand(), true);
        }
        return new FormModel($tokenKey, $messages);
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
}
