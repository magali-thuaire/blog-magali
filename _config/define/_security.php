<?php

// Nom des token Csrf
$token = [
	'SESSION' => ['contact', 'comment', 'authenticate']
];

$securityConst = [
	'INVALID_CSRF_TOKEN' 		=> 'CSRF token invalide',
];

$userConst = [
	'INVALID_CREDENTIALS'			=> 'Identifiants invalides',
	'USER_ERROR'					=> 'Veuillez renseigner un %s valide',
	'USER_ERROR_LOGIN'				=> ['USER_ERROR' => 'nom d\'utilisateur'],
	'USER_ERROR_PASSWORD' 			=> ['USER_ERROR' => 'mot de passe'],
	'USER_ERROR_VALIDATION' 		=> 'Connexion impossible pour le moment',
];