<?php

namespace App\Controller\Admin;

use App\Controller\AppController;
use Exception;

class AdminUserController extends AppController
{
    /**
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\LoaderError
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
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\LoaderError
     * @throws Exception
     */
    public function confirmValidate(int $id)
    {
        $user = $this->userManager->findOneById($id, $this->getUser());
        $form = $this->initForm('user-validate');

        $form->action = R_ADMIN_USER_VALIDATE . $user->getId();
        $form->message = ADMIN_USER_VALIDATED_MODAL_MESSAGE;

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
                $form->setSuccess(ADMIN_USER_VALIDATED_SUCCESS_MESSAGE);
            } else {
                $form->setError(ADMIN_USER_VALIDATED_ERROR_MESSAGE);
            }
        }

        $this->addMessage($form);
        header('Location: ' . R_ADMIN_USER);
    }

    /**
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\LoaderError
     * @throws Exception
     */
    public function confirmDelete(int $id)
    {
        $form = $this->initForm('user-delete');

        try {
            $user = $this->userManager->findOneById($id, $this->getUser());
        } catch (Exception $e) {
            $form->setError($e->getMessage());
        }

        $form->action = R_ADMIN_USER_DELETE . $user->getId();
        $form->message = ADMIN_USER_DELETED_MODAL_MESSAGE;

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
                $form->setSuccess(ADMIN_USER_DELETED_SUCCESS_MESSAGE);
            } else {
                $form->setError(ADMIN_USER_DELETED_ERROR_MESSAGE);
            }
        }

        $this->addMessage($form);
        header('Location: ' . R_ADMIN_USER);
    }
}
