<?php

use App\Controller\PostController;
use App\Controller\HomeController as HomeController;
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
	case $p === 'homepage':
		App::loadSession();
		$controller = new HomeController();
		$controller->index();
		break;
	case $p === 'contact' && $_POST:
		$controller = new HomeController();
		$controller->newContact();
		break;
	case $p === 'post':
		$controller = new PostController();
		switch(true) {
			// Demande de la page d'un article
			case isset($_GET['id']) && !empty($_GET['id'] && is_int($_GET['id'])):
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
	default:
		App::notFound();
}

