<?php

namespace App\Manager;

use App\Entity\CommentEntity;
use Core\Database\QueryBuilder;
use Core\Manager\EntityManager;

class CommentManager extends EntityManager
{

	public function newComment(CommentEntity $comment): bool
	{
		$statement = $this->newCommentQuery($comment)->getQuery();
		$attributs = [
				':content' => $comment->getContent(),
				':post'	=> $comment->getPost()->getId(),
				':author' => $comment->getAuthor(),
			];
		return $this->execute($statement, $attributs);
	}

	private function newCommentQuery(CommentEntity $comment): QueryBuilder
	{
		$qb = new QueryBuilder();
		$qb
			->insert('comment')
			->values('content', 'post', 'author');
		;

		return $qb;
	}
}