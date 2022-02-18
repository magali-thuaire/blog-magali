<?php

namespace App\Manager;

use App\Entity\CommentEntity;
use Core\Database\QueryBuilder;
use Core\Manager\EntityManager;

class CommentManager extends EntityManager
{
    //--------------------------------------------------------------
    //------- Requêtes SQL
    //--------------------------------------------------------------

    /**
     * Création d'un nouveau commentaire
     * @param CommentEntity $comment
     *
     * @return bool
     */
    public function new(CommentEntity $comment): bool
    {
        $statement = $this->createComment()->getQuery();
        $attributs = [
                ':content' => $comment->getContent(),
                ':post' => $comment->getPost()->getId(),
                ':author' => $comment->getAuthor(),
            ];
        return $this->execute($statement, $attributs);
    }

    //--------------------------------------------------------------
    //------- Query Builder
    //--------------------------------------------------------------

    /**
     * Retourne le QB de la création d'un nouveau commentaire
     * @return QueryBuilder
     */
    private function createComment(): QueryBuilder
    {
        return $this->createQueryBuilder()
            ->insert('comment')
            ->columns('content', 'post', 'author')
        ;
    }
}
