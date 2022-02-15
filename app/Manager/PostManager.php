<?php

namespace App\Manager;

use App\Entity\CommentEntity;
use App\Entity\PostEntity;
use App\Entity\UserEntity;
use Core\Database\QueryBuilder;
use Core\Manager\EntityManager;

class PostManager extends EntityManager
{
    /**
     * Retourne tous les articles publiés, ordonné du plus récent au plus ancien
     */
    public function findAllPublishedWithCommentsOrdereByNewest(): ?array
    {
        $statement = $this->getAllPublishedWithCommentsOrderByNewest()->getQuery();
        $postsData = $this->query($statement);

        $posts = [];
        if ($postsData) {
            /** @var PostEntity $post */
            foreach ($postsData as $postData) {
                $posts[] = $this->createPostWithAuthor($postData);
            }
        }

        return $posts;
    }

    /**
     * Retourne un unique article identifié à partir de son id
     */
    public function findOneByIdWithCommentsApproved(int $id): ?PostEntity
    {
        $statement = $this->getOneByIdWithCommentsApproved()->getQuery();
        $postsData = $this->prepare($statement, [':id' => $id], false, false);

        if ($postsData) {
            $post = $this->createPostWithAuthor(current($postsData));
            $this->addComments($post, $postsData);
            return $post;
        } else {
            return null;
        }
    }

    /**
     * Retourne la requête SQL de tous les articles
     */
    private function getAll(): QueryBuilder
    {
        return $this->createQueryBuilder()
            ->select('p.id', 'p.title', 'p.header', 'p.content', 'p.author', 'p.published')
            ->addSelect('p.published_at as publishedAt', 'p.created_at as createdAt', 'p.updated_at as updatedAt')
            ->addSelect('a.username')
            ->from('post', 'p')
            ->innerJoin('user', 'a', 'p.author = a.id')
        ;
    }

    /**
     * Retourne la requête SQL de tous les articles publiés
     */
    private function getAllPublished(): QueryBuilder
    {
        return $this->getAll()
            ->andWhere('p.published IS TRUE')
            ;
    }

    /**
     * Retourne la requête SQL de tous les articles
     */
    private function getAllPublishedWithComments(): QueryBuilder
    {
        return $this->getAllPublished()
            ->addCount('c.id', 'comments', 'p.id')
            ->leftJoin('comment', 'c', 'c.post = p.id', 'c.approved IS TRUE')
            ;
    }

    /**
     * Retourne la requête SQL de tous les article publiés, ordonnés du plus récent au plus ancien
     */
    private function getAllPublishedWithCommentsOrderByNewest(): QueryBuilder
    {

        return $this->getAllPublishedWithComments()
            ->orderBy('p.published_at', 'DESC')
        ;
    }

    /**
     * Retourne la requête SQL d'un unique article identifié à partir de son id
     */
    private function getOneById(): QueryBuilder
    {
        return $this->getAllPublished()
            ->andWhere('p.id = :id')
        ;
    }

    /**
     * Retourne la requête SQL d'un unique article identifié à partir de son id
     */
    private function getOneByIdWithCommentsApproved(): QueryBuilder
    {
        return $this->getOneById()
            ->addSelect('c.content as commentContent, c.created_at as commentCreatedAt, c.author as commentAuthor')
            ->leftJoin('comment', 'c', 'c.post = p.id', 'c.approved IS TRUE')
            ->orderBy('c.created_at', 'DESC')
        ;
    }

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

    /** Retourne un objet Post
     *
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

    /** Retourne un objet User qui est l'auteur de l'article
     *
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
