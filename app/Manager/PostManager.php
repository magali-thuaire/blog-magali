<?php

namespace App\Manager;

use App\Entity\PostEntity;
use App\Entity\UserEntity;
use App\Trait\PostTrait;
use Core\Database\QueryBuilder;
use Core\Manager\EntityManager;
use DateTime;
use Exception;

class PostManager extends EntityManager
{
    use PostTrait;

    /**
     * Retourne tous les articles publiés, ordonné du plus récent au plus ancien
     */
    public function findAllPublishedOrderedByNewest(): ?array
    {
        $statement = $this->getAllPublishedOrderedByNewest()->getQuery();
        $postsData = $this->query($statement);

        return $this->createPostsWithAuthor($postsData);
    }

    /**
     * Retourne un unique article identifié à partir de son id, avec ses commentaires approuvés
     */
    public function findOneByIdWithCommentsApproved(int $id): ?PostEntity
    {
        $statement = $this->getOneByIdWithCommentsApproved()->getQuery();
        $postData = $this->prepare($statement, [':id' => $id], false, false);

        return $this->createPostWithAuthorAndComments($postData);
    }

    /**
     * Retourne un unique article publié identifié à partir de son id
     */
    public function findOnePublishedById(int $id): ?PostEntity
    {
        $statement = $this->getOnePublishedById()->getQuery();
        $postData = $this->prepare($statement, [':id' => $id], true, false);

        return $this->createPostWithAuthor($postData);
    }

    /**
     * Retourne un unique article identifié à partir de son id
     */
    public function findOneById(int $id): ?PostEntity
    {
        $statement = $this->getOneById()->getQuery();
        $postData = $this->prepare($statement, [':id' => $id], true, false);

        return $this->createPostWithAuthor($postData);
    }

    /**

     * Retourne un unique article identifié à partir de son id si l'utilisateur est l'auteur
     */
    public function findOneByIdIfGranted(int $id, UserEntity $user): ?PostEntity
    {
        $qb = $this->getOneById()->andWhere('author = :author');
        $statement = $qb->getQuery();
        $attributs = [
            ':id' => $id,
            ':author' => $user->getId()
        ];
        $postData = $this->prepare($statement, $attributs, true, false);

        if (!$postData) {
            throw new Exception(ADMIN_POST_UPDATE_ERROR_MESSAGE);
        }
        return $this->createPostWithAuthor($postData);
    }

    /**
     * Retourne tous les articles d'un auteur, ordonné du plus récent au plus ancien
     */
    public function findAllByAuthorOrderedByNewest(UserEntity $user): ?array
    {
        $statement = $this->getAllByAuthorOrderedByNewest()->getQuery();
        $postsData = $this->prepare($statement, [':id' => $user->getId()]);

        return $this->createPostsWithAuthor($postsData);
    }

    public function delete(PostEntity $post, UserEntity $user): bool
    {
        $qb = $this->createQueryBuilder()
                    ->delete('post')
                    ->where('id = :id')
        ;

        $attributs = [':id' => $post->getId()];

        if ($user->getRole() !== UserEntity::ROLE_SUPERADMIN) {
            $qb->andWhere('author = :author');
            $attributs = array_merge($attributs, [':author' => $user->getId()]);
        }

        $statement = $qb->getQuery();
        return $this->execute($statement, $attributs);
    }

    public function update(PostEntity $post): bool
    {
        $qb = $this->createQueryBuilder()
            ->update('post', 'p')
            ->set('p.title = :title', 'p.header = :header', 'p.content = :content', 'p.published = :published', 'p.updated_at = :date')
            ->where('p.id = :id')
        ;

        $statement = $qb->getQuery();
        $attributs = [
            ':id'           => $post->getId(),
            ':title'        => $post->getTitle(),
            ':header'       => $post->getHeader(),
            ':content'      => $post->getContent(),
            ':published'    => $post->isPublished(),
            ':date'         => (new DateTime())->format('Y-m-d H:i:s')
        ];
        return $this->execute($statement, $attributs);
    }

    public function new(PostEntity $post): bool
    {
        $qb = $this->createQueryBuilder()
               ->insert('post')
               ->columns('title', 'header', 'content', 'published', 'author')
        ;

        $statement = $qb->getQuery();
        $attributs = [
            ':title'        => $post->getTitle(),
            ':header'       => $post->getHeader(),
            ':content'      => $post->getContent(),
            ':published'    => $post->isPublished(),
            ':author'       => $post->getAuthor()->getId()
        ];
        return $this->execute($statement, $attributs, true);
    }

    //--------------------------------------------------------------
    //------- Query Builder
    //--------------------------------------------------------------

    /**
     * Retourne le QB de tous les articles
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
     * Retourne le QB de tous les articles publiés
     */
    private function getAllPublished(): QueryBuilder
    {
        return $this->getAll()
            ->andWhere('p.published IS TRUE')
        ;
    }

    /**
     * Retourne le QB de tous les article publiés, ordonnés du plus récent au plus ancien
     */
    private function getAllPublishedOrderedByNewest(): QueryBuilder
    {
        return $this->getAllPublished()
            ->addCount('c.id', 'comments', 'p.id')
            ->leftJoin('comment', 'c', 'c.post = p.id', 'c.approved IS TRUE')
            ->orderBy('p.published_at', 'DESC')
        ;
    }

    /**
     * Retourne le QB d'un unique article publié identifié à partir de son id
     */
    private function getOnePublishedById(): QueryBuilder
    {
        return $this->getAllPublished()
            ->andWhere('p.id = :id')
        ;
    }

    /**
     * Retourne le QB d'un unique article identifié à partir de son id
     */
    private function getOneById(): QueryBuilder
    {
        return $this->getAll()
                ->andWhere('p.id = :id')
        ;
    }

    /**
     * Retourne le QB d'un unique article identifié à partir de son id
     */
    private function getOneByIdWithCommentsApproved(): QueryBuilder
    {
        return $this->getOnePublishedById()
            ->addSelect('c.content as commentContent, c.created_at as commentCreatedAt, c.author as commentAuthor')
            ->leftJoin('comment', 'c', 'c.post = p.id', 'c.approved IS TRUE')
            ->orderBy('c.created_at', 'DESC')
        ;
    }

    /**
     * Retourne le QB d'un unique article identifié à partir de son id
     */
    private function getAllByAuthorOrderedByNewest()
    {
        return $this->getAll()
            ->addCount('c.approved', 'comments', 'p.id')
            ->addSelect('sum(c.approved) as commentsApproved')
            ->leftJoin('comment', 'c', 'c.post = p.id')
            ->andWhere('a.id = :id')
        ;
    }
}
