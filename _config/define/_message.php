<?php

// Définition des messages
$contactConst = [
	'CONTACT_ERROR'				=> 'Veuillez renseigner un %s valide',
	'CONTACT_ERROR_NAME'		=> ['CONTACT_ERROR' => 'nom'],
	'CONTACT_ERROR_EMAIL' 		=> ['CONTACT_ERROR' => 'email'],
	'CONTACT_ERROR_MESSAGE' 	=> ['CONTACT_ERROR' => 'message'],
	'CONTACT_SUCCESS_MESSAGE' 	=> 'Votre message a bien été envoyé',
];

$securityConst = [
	'INVALID_CSRF_TOKEN' 		=> 'CSRF token invalide',
];

$emailConst = [
	'ERROR_SEND_SEMAIL' 		=> 'Problème lors de l\'envoi de l\'email',
	'EMAIL_DEFAULT_SUBJECT' 	=> 'Message de votre blog',
	'EMAIL_DEFAULT_TO' 			=> 'magali.thuaire@gmail.com'
];

$commentConst = [
	'COMMENT_SUCCESS_MESSAGE' => 'Votre commentaire a bien été enregistré, il sera visible après validation'
];