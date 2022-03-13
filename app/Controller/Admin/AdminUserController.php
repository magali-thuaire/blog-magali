<?php

namespace App\Controller\Admin;

use App\App;
use App\Controller\AppController;
use Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AdminUserController extends AppController
{
    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function index()
    {
        $users = $this->userManager->findAll();
        $app = $this->getMessage();

        $this->render('admin/user/index.twig', [
            'users' => $users,
            'app' => $app
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    public function confirmValidate(int $id): bool|string
    {
        $user = $this->userManager->findOneById($id, $this->getUser());
        $form = $this->initForm('user-validate');

        $form->action = App::$config['R_ADMIN_USER_VALIDATE'] . $user->getId();
        $form->message = App::$config['ADMIN_USER_VALIDATED_MODAL_MESSAGE'];

        return $this->render('admin/user/_modal.twig', [
            'user'    => $user,
            'form'    => $form
        ]);
    }

    /**
     * @throws Exception
     */
    public function validate(int $id)
    {
        $form = $this->createForm('user-validate');

        try {
            $user = $this->userManager->findOneById($id, $this->getUser());
        } catch (Exception $e) {
            $form->setError($e->getMessage());
        }

        if (!$form->hasError()) {
            $isValidated = $this->userManager->validate($user->getId(), $this->getUser());

            if ($isValidated) {
                $form->setSuccess(App::$config['ADMIN_USER_VALIDATED_SUCCESS_MESSAGE']);
            } else {
                $form->setError(App::$config['ADMIN_USER_VALIDATED_ERROR_MESSAGE']);
            }
        }

        $this->addMessage($form);
        header('Location: ' . App::$config['R_ADMIN_USER']);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    public function confirmDelete(int $id): bool|string
    {
        $form = $this->initForm('user-delete');

        try {
            $user = $this->userManager->findOneById($id, $this->getUser());
        } catch (Exception $e) {
            $form->setError($e->getMessage());
        }

        $form->action = App::$config['R_ADMIN_USER_DELETE'] . $user->getId();
        $form->message = App::$config['ADMIN_USER_DELETED_MODAL_MESSAGE'];

        return $this->render('admin/user/_modal.twig', [
            'user' => $user,
            'form' => $form
        ]);
    }

    /**
     * @throws Exception
     */
    public function delete(int $id)
    {
        $form = $this->createForm('user-delete');

        try {
            $user = $this->userManager->findOneById($id, $this->getUser());
        } catch (Exception $e) {
            $form->setError($e->getMessage());
        }

        if (!$form->hasError()) {
            $isDeleted = $this->userManager->delete($user, $this->getUser());

            if ($isDeleted) {
                $form->setSuccess(App::$config['ADMIN_USER_DELETED_SUCCESS_MESSAGE']);
            } else {
                $form->setError(App::$config['ADMIN_USER_DELETED_ERROR_MESSAGE']);
            }
        }

        $this->addMessage($form);
        header('Location: ' . App::$config['R_ADMIN_USER']);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    public function change(int $id)
    {
        $form = $this->initForm('user-update');

        try {
            $user = $this->userManager->findOneById($id, $this->getUser());
        } catch (Exception $e) {
            $form->setError($e->getMessage());
        }

        if ($form->hasError()) {
            $this->addMessage($form);
            header('Location: ' . App::$config['R_ADMIN']);
        }

        $this->render('admin/user/update.twig', [
            'form' => $form,
            'user' => $user
        ]);
    }

    /**
     * @throws Exception
     */
    public function update(int $id)
    {
        $form = $this->createForm('user-update');

        try {
            $user = $this->userManager->findOneById($id, $this->getUser());
        } catch (Exception $e) {
            $form->setError($e->getMessage());
        }

        if (!$form->hasError()) {
            $isAdminValidatedChange = (property_exists($form, 'adminValidated') !== $user->isAdminValidated());
            $user
                ->setAdminValidated(property_exists($form, 'adminValidated'))
                ->setRole($form->role)
            ;
            $isUpdated = $this->userManager->update($user, $isAdminValidatedChange);

            if ($isUpdated) {
                $form->setSuccess(App::$config['ADMIN_USER_UPDATED_SUCCESS_MESSAGE']);
            } else {
                $form->setError(App::$config['ADMIN_USER_UPDATE_ERROR_MESSAGE']);
            }
        }

        $this->addMessage($form);
        header('Location: ' . App::$config['R_ADMIN_USER']);
    }
}
