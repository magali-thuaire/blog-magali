<?php

namespace App\Controller\Admin;

use App\Controller\AppController;
use Exception;

class AdminPostController extends AppController
{
    public function index()
    {

        $posts = $this->postManager->findAllByAuthorOrderedByNewest($this->getUser());
        $app = $this->getMessage();

        $this->render('admin/post/index.twig', [
            'posts' => $posts,
            'app' => $app
        ]);
    }

    public function confirmDelete(int $id)
    {
        $post = $this->postManager->findOneById($id);
        $form = $this->initForm('post-delete');

        return $this->render('admin/post/_modal.delete.twig', [
            'post' => $post,
            'form' => $form
        ]);
    }

    /**
     * @throws Exception
     */
    public function delete(int $id)
    {
        $form = $this->createForm('post-delete');

        try {
            // Création du contact
            $post = $this->postManager->findOneById($id);
        } catch (Exception $e) {
            $form->setError($e->getMessage());
        }

        if (!$form->hasError()) {
            $isSuccess = $this->postManager->delete($post);

            if ($isSuccess) {
                $form->setSuccess(ADMIN_POST_DELETED_SUCCESS_MESSAGE);
            }
        }

        $this->addMessage($form);
        header('Location: ' . R_ADMIN);
    }
}
