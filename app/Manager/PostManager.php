<?php

namespace App\Manager;

use App\Entity\PostEntity;
use App\Trait\PostTrait;
use Core\Database\QueryBuilder;
use Core\Manager\EntityManager;

class PostManager extends EntityManager
{
    use PostTrait;

    /**
     * Retourne tous les articles publiés, ordonné du plus récent au plus ancien
     */
    public function findAllPublishedOrderedByNewest(): ?array
    {
        $statement = $this->getAllPublishedOrderByNewest()->getQuery();
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
     * Retourne un unique article identifié à partir de son id, avec ses commentaires approuvés
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
     * Retourne un unique article identifié à partir de son id
     */
    public function findOneById(int $id): ?PostEntity
    {
        $statement = $this->getOneById()->getQuery();
        $postData = $this->prepare($statement, [':id' => $id], true, false);

        if ($postData) {
            return $this->createPostWithAuthor($postData);
        } else {
            return null;
        }
    }

    //--------------------------------------------------------------
    //------- Requêtes SQL
    //--------------------------------------------------------------

    /**
     * Retourne la requête SQL de tous les articles publiés
     */
    private function getAllPublished(): QueryBuilder
    {
        return $this->createQueryBuilder()
            ->select('p.id', 'p.title', 'p.header', 'p.content', 'p.author', 'p.published')
            ->addSelect('p.published_at as publishedAt', 'p.created_at as createdAt', 'p.updated_at as updatedAt')
            ->addSelect('a.username')
            ->from('post', 'p')
            ->innerJoin('user', 'a', 'p.author = a.id')
            ->andWhere('p.published IS TRUE')
        ;
    }

    /**
     * Retourne la requête SQL de tous les article publiés, ordonnés du plus récent au plus ancien
     */
    private function getAllPublishedOrderByNewest(): QueryBuilder
    {
        return $this->getAllPublished()
            ->addCount('c.id', 'comments', 'p.id')
            ->leftJoin('comment', 'c', 'c.post = p.id', 'c.approved IS TRUE')
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
}
