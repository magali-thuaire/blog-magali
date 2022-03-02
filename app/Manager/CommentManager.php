<?php

namespace App\Manager;

use App\Entity\CommentEntity;
use App\Entity\UserEntity;
use App\Trait\CommentTrait;
use Core\Database\QueryBuilder;
use Core\Manager\EntityManager;
use Exception;

class CommentManager extends EntityManager
{
    use CommentTrait;

    //--------------------------------------------------------------
    //------- Requêtes SQL
    //--------------------------------------------------------------

    /**
     * Retourne un tableau de commentaires non approuvés pour un auteur d'article spécifique
     * @param UserEntity $user
     *
     * @return array|null
     */
    public function findAllUnapproved(UserEntity $user): ?array
    {
        $qb = $this->getAllUnapproved();

        $attributs = [];
        if ($user->getRole() !== UserEntity::ROLE_SUPERADMIN) {
            $qb->andWhere('p.author = :author');
            $attributs = array_merge($attributs, [':author' => $user->getId()]);
        }

        $statement = $qb->getQuery();

        $commentsData = $this->prepare($statement, $attributs, false, false);

        return $this->createCommentsWithPost($commentsData);
    }

    /**
     * Retourne un unique commentaire non approuvé identifié à partir de son id
     * @throws Exception
     */
    public function findOneUnapprovedByIdIfIsGranted(int $id, UserEntity $user): ?CommentEntity
    {
        $qb = $this->getOneUnapprovedById();
        $attributs = [
            ':id' => $id,
        ];
        if ($user->getRole() !== UserEntity::ROLE_SUPERADMIN) {
            $qb->andWhere('p.author = :author');
            $attributs = array_merge($attributs, [':author' => $user->getId()]);
        }

        $statement = $qb->getQuery();

        $commentData = $this->prepare($statement, $attributs, true, false);

        if (!$commentData) {
            throw new Exception(ADMIN_COMMENT_ERROR_MESSAGE);
        }

        return $this->createCommentWithPost($commentData);
    }

    /**
     * Retourne un unique commentaire identifié à partir de son id
     * @throws Exception
     */
    public function findOneByIdIfIsGranted(int $id, UserEntity $user): ?CommentEntity
    {
        $qb = $this->getOneById();
        $attributs = [
            ':id' => $id,
        ];

        if ($user->getRole() !== UserEntity::ROLE_SUPERADMIN) {
            $qb->andWhere('p.author = :author');
            $attributs = array_merge($attributs, [':author' => $user->getId()]);
        }

        $statement = $qb->getQuery();
        $commentData = $this->prepare($statement, $attributs, true, false);

        if (!$commentData) {
            throw new Exception(ADMIN_COMMENT_ERROR_MESSAGE);
        }

        return $this->createCommentWithPost($commentData);
    }

    /**
     * Création d'un nouveau commentaire
     * @param CommentEntity $comment
     *
     * @return bool
     */
    public function new(CommentEntity $comment): bool
    {
        $statement = $this->createCommentQuery()->getQuery();
        $attributs = [
            ':content' => $comment->getContent(),
            ':post' => $comment->getPost()->getId(),
            ':author' => $comment->getAuthor(),
        ];
        return $this->execute($statement, $attributs);
    }

    /**
     * Approuver un commentaire
     * @param CommentEntity $comment
     *
     * @return bool
     */
    public function approve(CommentEntity $comment): bool
    {
        $qb = $this->createQueryBuilder()
                   ->update('comment', 'c')
                   ->set('c.approved = TRUE')
                   ->where('c.id = :id')
        ;

        $statement = $qb->getQuery();

        return $this->execute($statement, [':id' => $comment->getId()]);
    }

    /**
     * Supprimer un commentaire
     * @param CommentEntity $comment
     * @param UserEntity    $user
     *
     * @return bool
     */
    public function delete(CommentEntity $comment, UserEntity $user): bool
    {
        $qb = $this->createQueryBuilder()
                   ->delete('comment', 'c', 'c')
                   ->innerJoin('post', 'p', 'p.id = c.post')
                   ->where('c.id = :id')
        ;

        $attributs = [':id' => $comment->getId()];

        if ($user->getRole() !== UserEntity::ROLE_SUPERADMIN) {
            $qb->andWhere('p.author = :author');
            $attributs = array_merge($attributs, [':author' => $user->getId()]);
        }

        $statement = $qb->getQuery();

        return $this->execute($statement, $attributs);
    }

    //--------------------------------------------------------------
    //------- Query Builder
    //--------------------------------------------------------------

    /**
     * Retourne le QB de la création d'un nouveau commentaire
     * @return QueryBuilder
     */
    private function createCommentQuery(): QueryBuilder
    {
        return $this->createQueryBuilder()
            ->insert('comment')
            ->columns('content', 'post', 'author')
        ;
    }

    /**
     * Retourne le QB de tous les commentaires
     */
    private function getAll(): QueryBuilder
    {
        return $this->createQueryBuilder()
                    ->select('c.id as commentId', 'c.content', 'c.author', 'c.created_at as createdAt')
                    ->from('comment', 'c')
                    ->innerJoin('post', 'p', 'p.id = c.post')
                    ->addSelect('p.id as postId, p.title')
        ;
    }

    /**
     * Retourne le QB de tous les commentaires non approuvés identifié par l'auteur des articles
     */
    private function getAllUnapproved(): QueryBuilder
    {
        return $this->getAll()
                    ->where('c.approved = FALSE')
        ;
    }

    /**
     * Retourne le QB d'un commentaire non approuvé identifié à partir de son id
     */
    private function getOneUnapprovedById(): QueryBuilder
    {
        return $this->getAllUnapproved()
                    ->andWhere('c.id = :id')
        ;
    }

    /**
     * Retourne le QB d'un commentaire identifié à partir de son id
     */
    private function getOneById(): QueryBuilder
    {
        return $this->getAll()
                    ->andWhere('c.id = :id')
        ;
    }
}
