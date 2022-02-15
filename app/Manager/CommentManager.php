<?php

namespace App\Manager;

use App\Entity\CommentEntity;
use Core\Database\QueryBuilder;
use Core\Manager\EntityManager;

class CommentManager extends EntityManager
{
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

    private function createComment(): QueryBuilder
    {
        return $this->createQueryBuilder()
            ->insert('comment')
            ->values('content', 'post', 'author')
        ;
    }
}
