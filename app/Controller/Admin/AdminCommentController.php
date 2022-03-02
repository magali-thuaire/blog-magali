<?php

namespace App\Controller\Admin;

use App\Controller\AppController;
use Exception;

class AdminCommentController extends AppController
{
    /**
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\LoaderError
     */
    public function index()
    {
        $comments = $this->commentManager->findAllUnapproved($this->getUser());
        $app = $this->getMessage();

        $this->render('admin/comment/index.twig', [
            'comments' => $comments,
            'app' => $app
        ]);
    }

    /**
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\LoaderError
     * @throws Exception
     */
    public function confirmApprove(int $id)
    {
        $comment = $this->commentManager->findOneUnapprovedByIdIfIsGranted($id, $this->getUser());
        $form = $this->initForm('comment-approve');

        return $this->render('admin/comment/_modal.approve.twig', [
            'comment' => $comment,
            'form'    => $form
        ]);
    }

    /**
     * @throws Exception
     */
    public function approve(int $id)
    {
        $form = $this->createForm('comment-approve');

        try {
            $comment = $this->commentManager->findOneUnapprovedByIdIfIsGranted($id, $this->getUser());
        } catch (Exception $e) {
            $form->setError($e->getMessage());
        }

        if (!$form->hasError()) {
            $isApproved = $this->commentManager->approve($comment);

            if ($isApproved) {
                $form->setSuccess(ADMIN_COMMENT_APPROVED_SUCCESS_MESSAGE);
            } else {
                $form->setError(ADMIN_COMMENT_APPROVED_ERROR_MESSAGE);
            }
        }

        $this->addMessage($form);
        header('Location: ' . R_ADMIN_COMMENT);
    }

    /**
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\LoaderError
     * @throws Exception
     */
    public function confirmDelete(int $id)
    {
        $form = $this->initForm('comment-delete');

        try {
            // Création du commentaire
            $comment = $this->commentManager->findOneUnapprovedByIdIfIsGranted($id, $this->getUser());
        } catch (Exception $e) {
            $form->setError($e->getMessage());
        }

        return $this->render('admin/comment/_modal.delete.twig', [
            'comment' => $comment,
            'form' => $form
        ]);
    }

    /**
     * @throws Exception
     */
    public function delete(int $id)
    {
        $form = $this->createForm('comment-delete');

        try {
            $comment = $this->commentManager->findOneByIdIfIsGranted($id, $this->getUser());
        } catch (Exception $e) {
            $form->setError($e->getMessage());
        }

        if (!$form->hasError()) {
            $isDeleted = $this->commentManager->delete($comment, $this->getUser());

            if ($isDeleted) {
                $form->setSuccess(ADMIN_COMMENT_DELETED_SUCCESS_MESSAGE);
            } else {
                $form->setError(ADMIN_COMMENT_DELETED_ERROR_MESSAGE);
            }
        }

        $this->addMessage($form);
        header('Location: ' . R_ADMIN_COMMENT);
    }
}
