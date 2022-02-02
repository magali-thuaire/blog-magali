<?php

use App\Controller\HomeController as HomeController;
use Core\Security;

require_once '../_config/_define.php';
require APP . '/App.php';
App::load();

// Récupération de la page à partir de l'URL
if(isset($_GET['p']) && !empty($_GET['p'])) {
	$p = Security::checkInput($_GET['p']);
} else {
	$p = 'homepage';
}

if($p === 'homepage') {
	$controller = new HomeController();
	$controller->index();
} elseif($p === 'contact') {
	$controller = new HomeController();
	$controller->newContact();
} else {
	App::not_found();
}