<?php

namespace App\Controller;

use App\App;
use App\Entity\CommentEntity;
use App\Entity\PostEntity;
use Core\Security\CsrfToken;
use Core\Security\Security;
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
        $posts = $this->postManager->findAllPublishedWithCommentsOrdereByNewest();

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

        $form = $this->createForm('comment');

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
        // Nettoyage des données postées
        $formData = Security::checkInputs($_POST);

        // Récupération du token
        $token = new CsrfToken('comment', $formData['csrfToken']);
        unset($formData['csrfToken']);
        // Initialisation du formulaire avec données nettoyées
        $form = $this
            ->createForm('comment', false)
            ->hydrate($formData);

        if ($form->isTokenValid($token)) {
            try {
                // Création du commentaire
                $comment = new CommentEntity();
                $comment->hydrate($formData);
                $post = $this->postManager->findOneByIdWithCommentsApproved($id);

                // Si l'article existe bien
                if ($post) {
                    $comment->setPost($post);
                } else {
                    $form->setError(COMMENT_ERROR_ARTICLE);
                }
            } catch (Exception $e) {
                $form->setError($e->getMessage());
            }
        } else {
            throw new Exception('Invalid CSRF token');
        }

        if (!$form->getError()) {
             $isSuccess = $this->commentManager->new($comment);

            if ($isSuccess) {
                $form->setSuccess(COMMENT_SUCCESS_MESSAGE);
            }
        }

        // Données du formulaire en json
        echo json_encode(['form' => $form]);
    }
}
