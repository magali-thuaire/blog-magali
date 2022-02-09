<?php

namespace App\Controller;

use App;
use App\Entity\CommentEntity;
use App\Entity\PostEntity;
use App\Manager\CommentManager;
use App\Manager\Postmanager;
use Core\Model\FormModel;
use Core\Security\CsrfToken;
use Core\Security\Security;
use Exception;

class PostController extends AppController
{
	/** @var Postmanager */
	private $postManager;
	/** @var CommentManager */
	private $commentManager;

	public function __construct()
	{
		$this->postManager = $this->getManager('post');
		$this->commentManager = $this->getManager('comment');
	}

	public function index()
	{
		/** @var PostEntity[]|null $posts */
		$posts = $this->postManager->findAllPublishedWithCommentsOrdereByNewest();

		$this->render('post.index', [
			'posts' => $posts
		]);
	}

	public function show(int $id)
	{
		/** @var PostEntity|null $posts */
		$post = $this->postManager->findOneByIdWithCommentsApproved($id);

		if(!$post) {
			App::not_found();
		}

		$form = $this->initCommentForm();

		$this->render('post.show', [
			'post' => $post,
			'form' => $form
		]);
	}

	public function newComment(int $id)
	{
		// Nettoyage des données postées
		$formData = Security::checkInputs($_POST);

		// Récupération du token
		$token = new CsrfToken('comment', $formData['csrfToken']);
		unset($formData['csrfToken']);

		// Initialisation du formulaire avec données nettoyées
		$form = $this
			->initCommentForm()
			->hydrate($formData);

		if($form->isTokenValid($token)) {
			try {
				// Création du commentaire
				$comment = new CommentEntity();
				$comment->hydrate($formData);
				$post = $this->postManager->findOneByIdWithCommentsApproved($id);
				$comment->setPost($post);
			} catch (Exception $e) {
				$form->setError($e->getMessage());
			}
		} else {
			throw new Exception('Invalid CSRF token');
		}

		if(!$form->getError()) {

			 $isSuccess = $this->commentManager->newComment($comment);

			 if($isSuccess) {
				 $form->setSuccess(COMMENT_SUCCESS_MESSAGE);
			 }
		}

		// Données du formulaire en json
		echo json_encode(['form' => $form]);

	}

	private function initCommentForm()
	{
		return new FormModel('comment');
	}
}