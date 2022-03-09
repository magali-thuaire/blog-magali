<?php

namespace App\Controller;

use App\App;
use App\Entity\CommentEntity;
use App\Entity\PostEntity;
use Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PostController extends AppController
{
    /**
     * Demande la page des articles publiés du plus récent au plus ancien
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function index()
    {
        /** @var PostEntity[]|null $posts */
        $posts = $this->postManager->findAllPublishedOrderedByNewest();

        return $this->render('post/index.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * Demande un article identifié par son id
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    public function show(int $id)
    {
        /** @var PostEntity|null $posts */
        $post = $this->postManager->findOneByIdWithCommentsApproved($id);
        if (!$post) {
            return App::getInstance()->notFound();
        }

        $form = $this->initForm('comment');

        if ($this->getUser()) {
            $form->author = $this->getUser();
        }

        $this->render('post/show.twig', [
            'post' => $post,
            'form' => $form
        ]);
    }

    /**
     * Demande l'ajout d'un commentaire
     * @throws Exception
     */
    public function newComment(int $id)
    {
        $form = $this->createForm('comment');

        try {
            // Création du commentaire
            $comment = new CommentEntity();
            $comment->hydrate((array) $form);
            $post = $this->postManager->findOnePublishedById($id);
        } catch (Exception $e) {
            $form->setError($e->getMessage());
        }

        // Si l'article existe bien
        if ($post) {
            $comment->setPost($post);
        } else {
            $form->setError(App::$config['COMMENT_ERROR_ARTICLE']);
        }

        if (!$form->hasError()) {
             $isSuccess = $this->commentManager->new($comment);

            if ($isSuccess) {
                $form->setSuccess(App::$config['COMMENT_SUCCESS_MESSAGE']);
            }
        }

        // Données du formulaire en json
        return print_r(json_encode(['form' => $form]));
    }
}
