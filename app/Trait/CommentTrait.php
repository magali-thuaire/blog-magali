<?php

namespace App\Trait;

use App\App;
use App\Entity\CommentEntity;
use App\Entity\PostEntity;
use Exception;

trait CommentTrait
{
    /**
     * Retourne un tableau de Comment dont les articles sont des objets articles
     * @throws Exception
     */
    private function createCommentsWithPost($commentsData): ?array
    {
        $comments = [];
        if ($commentsData) {
            foreach ($commentsData as $commentData) {
                $comments[] = $this->createCommentWithPost($commentData);
            }
        }

        return $comments;
    }

    /**
     * Retourne un objet Comment dont l'article est un object Post
     * @throws Exception
     */
    private function createCommentWithPost($data): CommentEntity
    {

        if (!$data) {
            throw new Exception(App::$config['COMMENT_ERROR']);
        }

        // Création de l'article
        $post = $this->createPost($data);

        // Création du commentaire
        $comment = $this->createComment($data);
        $comment->setPost($post);

        return $comment;
    }

    /**
     * Retourne un objet Comment
     */
    private function createComment($data): CommentEntity
    {
        // Création du commentaire
        $commentData = [
            'id'                => $data->commentId,
            'username'          => $data->username,
            'content'           => $data->content,
            'createdAt'         => $data->createdAt,
        ];

        $comment = new CommentEntity();
        $comment->hydrate($commentData);

        return $comment;
    }

    /**
     * Retourne un objet Post qui est l'article du commentaire
     */
    private function createPost($data): PostEntity
    {
        $postData = [
            'id'      => $data->postId,
            'title'   => $data->title
        ];
        $post = new PostEntity();
        $post->hydrate($postData);

        return $post;
    }
}
