<?php

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

// Tableau des pages
$pages = [
	'homepage' => VIEWS . '/homepage/index.php',
];

// Si la page demandée n'existe pas
if(!array_key_exists($p, $pages)) {
	require_once VIEWS . '/security/404.html';
} else {
	// Stocke dans une variable tout ce qui est affiché
	ob_start();

	if($p === 'homepage') {
		require_once $pages[$p];
	}

	$content = ob_get_clean();

	require_once VIEWS . '/base.php';
}