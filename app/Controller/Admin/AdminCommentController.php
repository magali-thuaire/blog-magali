<?php

namespace App\Controller\Admin;

use App\App;
use App\Controller\AppController;
use Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AdminCommentController extends AppController
{
    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
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
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    public function confirmApprove(int $id)
    {
        $comment = $this->commentManager->findOneUnapprovedByIdIfIsGranted($id, $this->getUser());
        $form = $this->initForm('comment-approve');

        $form->action = App::$config['R_ADMIN_COMMENT_APPROVE'] . $comment->getId();
        $form->message = App::$config['ADMIN_COMMENT_APPROVED_MODAL_MESSAGE'];

        return $this->render('admin/comment/_modal.twig', [
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
                $form->setSuccess(App::$config['ADMIN_COMMENT_APPROVED_SUCCESS_MESSAGE']);
            } else {
                $form->setError(App::$config['ADMIN_COMMENT_APPROVED_ERROR_MESSAGE']);
            }
        }

        $this->addMessage($form);
        header('Location: ' . App::$config['R_ADMIN_COMMENT']);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
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

        $form->action = App::$config['R_ADMIN_COMMENT_DELETE'] . $comment->getId();
        $form->message = App::$config['ADMIN_COMMENT_DELETED_MODAL_MESSAGE'];

        return $this->render('admin/comment/_modal.twig', [
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
                $form->setSuccess(App::$config['ADMIN_COMMENT_DELETED_SUCCESS_MESSAGE']);
            } else {
                $form->setError(App::$config['ADMIN_COMMENT_DELETED_ERROR_MESSAGE']);
            }
        }

        $this->addMessage($form);
        header('Location: ' . App::$config['R_ADMIN_COMMENT']);
    }
}
