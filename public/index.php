<?php

use App\Controller\PostController;
use App\Controller\HomeController as HomeController;
use App\Controller\SecurityController;
use Core\Security\Security;

require_once '../_config/_define.php';
require_once APP . '/App.php';
App::load();

// Récupération de la page à partir de l'URL
if(isset($_GET['p']) && !empty($_GET['p'])) {
	$p = Security::checkInput($_GET['p']);
} else {
	$p = 'homepage';
}

switch(true) {
	// Demande de la page d'accueil
	case $p === 'homepage':
		App::loadSession();
		$controller = new HomeController();
		$controller->index();
		break;
	// Validation du formulaire de contact
	case $p === 'contact' && $_POST:
		$controller = new HomeController();
		$controller->newContact();
		break;
	case $p === 'post':
		$controller = new PostController();
		switch(true) {
			// Demande de la page d'un article
			case isset($_GET['id']) && !empty($_GET['id'] && is_int((int) $_GET['id'])):
				$id = Security::checkInput($_GET['id']);
				switch($_POST) {
					// Demande d'ajout d'un commentaire
					case true:
						$controller->newComment($id);
						break;
					// Visualisation d'un article
					default:
						App::loadSession();
						$controller->show($id);
				}
				break;
			// Demande de visualisation des articles
			default:
				App::loadSession();
				$controller->index();
		}
		break;
	case $p === 'login':
		$controller = new SecurityController();
		switch($_POST) {
			case true:
				$controller->authenticate();
				break;
			default:
				App::loadSession();
				$controller->login();
		}
		break;
	case $p === 'register':
		$controller = new SecurityController();
		switch($_POST) {
			// TODO: Validation du formulaire d'inscription
			case true:
				$controller->register();
				break;
			default:
				App::loadSession();
				$controller->signin();
		}
		break;
	default:
		App::notFound();
}

