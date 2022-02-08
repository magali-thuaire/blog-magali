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
	public function findAllPublishedOrdereByNewest(): ?array
	{
		$statement = $this->getAllPublishedOrderByNewest()->getQuery();
		$postsData = $this->query($statement);

		$posts = [];
		if($postsData) {
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
		$postsData = $this->prepare($statement, [':id' => $id], false);

		if($postsData) {
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
		$qb = new QueryBuilder();
		return $qb
			->select('p.id', 'p.title', 'p.header', 'p.content', 'p.author', 'p.published', 'p.published_at as publishedAt', 'p.created_at as createdAt', 'p.updated_at as updatedAt')
			->addSelect('a.username')
			->from('post', 'p')
			->innerJoin('user', 'a', 'p.author = a.id')
			->innerJoin('role', 'r', 'r.id = a.role');
	}

	/**
	 * Retourne la requête SQL de tous les articles publiés
	 */
	private function getAllPublished(): QueryBuilder {
		return $this->getAll()
			->andWhere('p.published IS TRUE');
	}

	/**
	 * Retourne la requête SQL de tous les article publiés, ordonnés du plus récent au plus ancien
	 */
	private function getAllPublishedOrderByNewest(): QueryBuilder {
		return $this->getAllPublished()
			->orderBy('p.published_at', 'DESC');
	}

	/**
	 * Retourne la requête SQL d'un unique article identifié à partir de son id
	 */
	private function getOneById(): QueryBuilder
	{
		return $this->getAllPublished()
			->andWhere('p.id = :id');
	}

	/**
	 * Retourne la requête SQL d'un unique article identifié à partir de son id
	 */
	private function getOneByIdWithCommentsApproved(): QueryBuilder
	{
		return $this->getOneById()
			->addSelect('c.content as commentContent, c.created_at as commentCreatedAt')
			->addSelect('ca.username as commentAuthor')
			->leftJoin('comment', 'c', 'c.post = p.id', 'c.approved IS TRUE')
			->leftJoin('user', 'ca', 'ca.id = c.author');
	}

	/**
	 * Retourne un object Post dont son author est un object User
	 */
	private function createPostWithAuthor($data): PostEntity {

		$authorData = [
			'username' 			=> $data->username
		];
		$author = new UserEntity();
		$author->hydrate($authorData);

		$postData = [
			'id' 				=> $data->id,
			'title' 			=> $data->title,
			'header' 			=> $data->header,
			'content' 			=> $data->content,
			'author' 			=> $author,
			'published' 		=> $data->published,
			'publishedAt' 		=> $data->publishedAt,
			'createdAt' 		=> $data->createdAt,
			'updatedAt' 		=> $data->updatedAt,
		];
		
		$post = new PostEntity();
		return $post->hydrate($postData);

	}

	/**
	 * Retourne un object Post dont ses commentaires sont des objets Comment
	 */
	private function addComments(PostEntity $post, $postData): PostEntity
	{
		foreach ($postData as $data) {

			// Si l'article dispose de commentaires
			if ($data->commentAuthor) {

				$commentAuthorData = [
					'username' => $data->commentAuthor
				];
				$author = new UserEntity();
				$author->hydrate($commentAuthorData);

				$commentData = [
					'content' => $data->commentContent,
					'createdAt' => $data->commentCreatedAt,
					'author' => $author,
				];

				$comment = new CommentEntity();
				$comment->hydrate($commentData);
				$post->addComment($comment);
			}
		}

		return $post;

	}
	
}