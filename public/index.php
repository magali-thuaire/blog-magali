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

if($p === 'homepage') {
	App::loadSession();
	$controller = new HomeController();
	$controller->index();
} elseif($p === 'contact' && $_POST) {
	$controller = new HomeController();
	$controller->newContact();
} elseif($p === 'post') {
	$controller = new PostController();
	// Demande de visualisation d'un article
	if(isset($_GET['id']) && !empty($_GET['id'])) {
		$id = Security::checkInput($_GET['id']);
		$controller->show($id);
	} else {
		// Demande de visualisation des articles
		$controller->index();
	}
} else {
	App::not_found();
}