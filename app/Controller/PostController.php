<?php

namespace App\Controller;

use App\Entity\PostEntity;
use App\Manager\Postmanager;
use App\Manager\UserManager;

class PostController extends AppController
{
	/** @var Postmanager */
	private $postManager;

	public function __construct()
	{
		$this->postManager = $this->getManager('post');
	}

	public function index()
	{
		/** @var PostEntity[]|null $posts */
		$posts = $this->postManager->findAllPublishedOrdereByNewest();

		$this->render('post.index', [
			'posts' => $posts
		]);
	}
}