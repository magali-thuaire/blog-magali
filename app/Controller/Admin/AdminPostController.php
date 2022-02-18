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
            $post = $this->postManager->findOneById($id);
        } catch (Exception $e) {
            $form->setError($e->getMessage());
        }

        if (!$form->hasError()) {
            $isDeleted = $this->postManager->delete($post, $this->getUser());

            if ($isDeleted) {
                $form->setSuccess(ADMIN_POST_DELETED_SUCCESS_MESSAGE);
            } else {
                $form->setError(ADMIN_POST_DELETED_ERROR_MESSAGE);
            }
        }

        $this->addMessage($form);
        header('Location: ' . R_ADMIN);
    }

    public function change(int $id)
    {
        $form = $this->initForm('post-update');

        try {
            $post = $this->postManager->findOneByIdIfGranted($id, $this->getUser());
        } catch (Exception $e) {
            $form->setError($e->getMessage());
        }

        if ($form->hasError()) {
            $this->addMessage($form);
            header('Location: ' . R_ADMIN);
        }

        $this->render('admin/post/update.twig', [
            'form' => $form,
            'post' => $post
        ]);
    }

    public function update(int $id)
    {
        $form = $this->createForm('post-update');

        try {
            $post = $this->postManager->findOneByIdIfGranted($id, $this->getUser());
        } catch (Exception $e) {
            $form->setError($e->getMessage());
        }

        if (!$form->hasError()) {
            $post
                ->setTitle($form->title)
                ->setHeader($form->header)
                ->setContent($form->content)
                ->setPublished(property_exists($form, 'published') ? true : false)
            ;
            $isUpdated = $this->postManager->update($post);

            if ($isUpdated) {
                $form->setSuccess(ADMIN_POST_UPDATED_SUCCESS_MESSAGE);
            } else {
                $form->setError(ADMIN_POST_UPDATE_ERROR_MESSAGE);
            }
        }

        $this->addMessage($form);
        header('Location: ' . R_ADMIN);
    }
}
