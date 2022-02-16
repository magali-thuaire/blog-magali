<?php

namespace App\Trait;

use App\Entity\CommentEntity;
use App\Entity\PostEntity;
use App\Entity\UserEntity;

trait PostTrait
{
    /**
     * Retourne un object Post dont son author est un object User
     */
    private function createPostWithAuthor($data): PostEntity
    {

        // Création de l'auteur
        $author = $this->createAuthor($data);

        // Création de l'article
        $post = $this->createPost($data);
        $post->setAuthor($author);

        // Création des commentaires
        if (property_exists($data, 'comments')) {
            while ($data->comments-- > 0) {
                $comment = new CommentEntity();
                $post->addComment($comment);
            }
        }

        return $post;
    }

    /**
     * Retourne un objet Post
     */
    private function createPost($data): PostEntity
    {
        // Création de l'article
        $postData = [
            'id'                => $data->id,
            'title'             => $data->title,
            'header'            => $data->header,
            'content'           => $data->content,
            'published'         => $data->published,
            'publishedAt'       => $data->publishedAt,
            'createdAt'         => $data->createdAt,
            'updatedAt'         => $data->updatedAt,
        ];

        $post = new PostEntity();
        $post->hydrate($postData);

        return $post;
    }

    /**
     * Retourne un objet User qui est l'auteur de l'article
     */
    private function createAuthor($data): UserEntity
    {
        $authorData = [
            'username'          => $data->username
        ];
        $author = new UserEntity();
        $author->hydrate($authorData);

        return $author;
    }

    /**
     * Retourne un object Post dont ses commentaires sont des objets Comment
     */
    private function addComments(PostEntity $post, $postData): void
    {
        foreach ($postData as $data) {
            // Si l'article dispose de commentaires
            if ($data->commentAuthor) {
                $commentData = [
                    'content' => $data->commentContent,
                    'createdAt' => $data->commentCreatedAt,
                    'author' => $data->commentAuthor,
                ];

                $comment = new CommentEntity();
                $comment->hydrate($commentData);
                $post->addComment($comment);
            }
        }
    }
}
