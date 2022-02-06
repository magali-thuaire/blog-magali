<?php

namespace App\Manager;

use App\Entity\PostEntity;
use App\Entity\UserEntity;
use Core\Manager\EntityManager;

class PostManager extends EntityManager
{

	public function findAllPublishedOrdereByNewest(): array
	{
		$posts = [];
		$postsData = $this->query($this->getAllPublishedOrderByNewest());

		if($postsData) {
			/** @var PostEntity $post */
			foreach ($postsData as $postData) {
				$posts[] = $this->getPostWithAuthor($postData);
			}
		}

		return $posts;
	}

	private function getAll(): string
	{
		return
			'SELECT
				p.id as postId,
       			p.title,
       			p.header,
       			p.content,
       			p.author,
       			p.published,
       			p.published_at as postPublishedAt,
       			p.created_at as postCreatedAt,
       			p.updated_at as postUpdatedAt,
       			u.id as userId,
       			u.username,
       			u.email,
       			u.login,
       			u.password,
       			u.validation_token as validationToken,
       			u.user_confirmed as userConfirmed,
       			u.admin_validated as adminValidated,
       			u.created_at as userCreatedAt,
       			r.name as role
			FROM post p
				INNER JOIN user u ON u.id = p.author
				INNER JOIN role r ON r.id = u.role
			';
	}
	
	private function getAllPublished(): string {
		return $this->getAll() . ' WHERE published is TRUE';
	}

	private function getAllPublishedOrderByNewest(): string {
		return $this->getAllPublished() . ' ORDER BY published_at DESC';
	}

	private function getPostWithAuthor($data): PostEntity {

		$authorData = [
			'id' => $data->userId,
			'username' => $data->username,
			'email' => $data->email,
			'login'  => $data->login,
			'password'  => $data->password,
			'validationToken'  => $data->validationToken,
			'userConfirmed' => $data->userConfirmed,
			'adminValidated'  => $data->adminValidated,
			'createdAt' => $data->userCreatedAt,
			'role' => $data->role,
		];
		$author = new UserEntity();
		$author->hydrate($authorData);

		$postData = [
			'id' => $data->postId,
			'title' => $data->title,
			'header' => $data->header,
			'content' => $data->content,
			'author' => $author,
			'published' => $data->published,
			'publishedAt' => $data->postPublishedAt,
			'createdAt' => $data->postCreatedAt,
			'updatedAt' => $data->postUpdatedAt,
		];

		$post = new PostEntity();
		return $post->hydrate($postData);

	}
}