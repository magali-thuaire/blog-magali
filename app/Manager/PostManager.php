<?php

namespace App\Manager;

use Core\Manager\EntityManager;

class PostManager extends EntityManager
{
	public function findAllPublishedOrdereByNewest()
	{
		return $this->query($this->getAllPublishedOrderByNewest());
	}

	private function getAll(): string
	{
		return
			'SELECT
				id,
       			title,
       			header,
       			content,
       			author,
       			published,
       			published_at as publishedAt,
       			created_at as createdAt,
       			updated_at as updatedAt
			FROM post';
	}
	
	private function getAllPublished(): string {
		return $this->getAll() . ' WHERE published is TRUE';
	}

	private function getAllPublishedOrderByNewest(): string {
		return $this->getAllPublished() . ' ORDER BY published_at DESC';
	}
}