<?php

namespace App\Trait;

use App\Entity\CommentEntity;
use App\Entity\PostEntity;
use App\Entity\UserEntity;
use Exception;

trait PostTrait
{
    /**
     * Retourne un tableau de Post dont les auteurs sont des objets User
     * @throws Exception
     */
    private function createPostsWithAuthor($postsData): ?array
    {
        $posts = [];
        if ($postsData) {
            foreach ($postsData as $postData) {
                $posts[] = $this->createPostWithAuthor($postData);
            }
        }

        return $posts;
    }

    /**
     * Retourne un objet Post dont les commentaires sont des objets Comment et dont l'auteur est un objet User
     * @throws Exception
     */
    public function createPostWithAuthorAndComments($postData): ?PostEntity
    {
        if ($postData) {
            $post = $this->createPostWithAuthor(current($postData));
            $this->addComments($post, $postData);
            return $post;
        } else {
            return null;
        }
    }

    /**
     * Retourne un objet Post dont l'auteur est un object User
     * @throws Exception
     */
    private function createPostWithAuthor($data): PostEntity
    {

        if (!$data) {
            throw new Exception(POST_ERROR);
        }

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

        if (property_exists($data, 'commentsApproved')) {
            $post->commentsApproved = $data->commentsApproved;
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
     * Retourne un objet Post dont ses commentaires sont des objets Comment
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
                    'approved' => (property_exists($data, 'approved')) ? $data->approved : false
                ];

                $comment = new CommentEntity();
                $comment->hydrate($commentData);
                $post->addComment($comment);
            }
        }
    }
}
