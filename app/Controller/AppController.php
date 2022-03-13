<?php

namespace App\Controller;

use App\App;
use App\Entity\UserEntity;
use App\Security\Security;
use Core\Manager\EntityManager;
use Core\Model\FormModel;
use Core\Security\CsrfToken;
use Core\Service\Request;
use Exception;
use JetBrains\PhpStorm\Pure;
use stdClass;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AppController
{
    protected Request $request;

    public function __construct()
    {
        $this->request = App::request();
    }

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
    protected function render($view, $variables = []): bool|string
    {
        $render_config =  [
            'roles'             => [UserEntity::ROLE_USER, UserEntity::ROLE_ADMIN, UserEntity::ROLE_SUPERADMIN],
            'roles_admin'       => [UserEntity::ROLE_ADMIN, UserEntity::ROLE_SUPERADMIN],
            'role_superadmin'   => UserEntity::ROLE_SUPERADMIN,
        ];
        App::$render_config = array_merge(App::$render_config, $render_config);

        return App::getInstance()->getRenderer()->render($view, array_merge(App::$render_config, $variables));
    }

    /**
     * Retourne un nouveau formulaire avec l'initialisation d'un token csrf
     * @throws Exception
     */
    protected function initForm(string $tokenKey, bool $tokenInitialize = true): FormModel
    {
        if ($tokenInitialize) {
            $this->request->set($tokenKey, uniqid(rand(), true));
        }

        $messages = $this->request->get('session', 'messages') ?? [];
        $this->request->unset('messages');

        return new FormModel($this->request, $tokenKey, $messages);
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
        $formData = $this->request->all('request');

        // Récupération du token
        $token = new CsrfToken($tokenKey, $formData['csrfToken']);
        unset($formData['csrfToken']);

        // Initialisation du formulaire avec données nettoyées
        $form = $this
            ->initForm($tokenKey, false)
            ->hydrate($formData);

        if (!$form->isTokenValid($token)) {
            throw new Exception(App::$config['INVALID_CSRF_TOKEN']);
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

    /**
     */
    #[Pure] protected function getUser()
    {
        return Security::getUser();
    }

    protected function addMessage(FormModel $form): void
    {
        $this->request->set('success', $form->getSuccess());
        $this->request->set('error', $form->getError());
    }

    protected function getMessage(): stdClass
    {
        $app = new stdClass();

        if ($success = $this->request->get('session', 'success')) {
            $app->success = $success;
            $this->request->unset('success');
        }
        if ($error = $this->request->get('session', 'error')) {
            $app->error = $error;
            $this->request->unset('error');
        }

        return $app;
    }

    public function __invoke(string $method, $params = null)
    {
        if ($params) {
            return call_user_func_array([$this, "$method"], $params);
        }
        return $this->$method($params);
    }
}
