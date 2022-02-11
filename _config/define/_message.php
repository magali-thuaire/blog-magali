<?php

// Définition des messages
$contactConst = [
    'CONTACT_ERROR'             => 'Veuillez renseigner un %s valide',
    'CONTACT_ERROR_NAME'        => ['CONTACT_ERROR' => 'nom'],
    'CONTACT_ERROR_EMAIL'       => ['CONTACT_ERROR' => 'email'],
    'CONTACT_ERROR_MESSAGE'     => ['CONTACT_ERROR' => 'message'],
    'CONTACT_SUCCESS_MESSAGE'   => 'Votre message a bien été envoyé',
];

$emailConst = [
    'ERROR_SEND_SEMAIL'         => 'Problème lors de l\'envoi de l\'email',
    'EMAIL_DEFAULT_SUBJECT'     => 'Message de votre blog',
    'EMAIL_DEFAULT_TO'          => 'magali.thuaire@gmail.com',
    'EMAIL_DEFAULT_FROM'        => 'magali.thuaire@gmail.com'
];

$commentConst = [
    'COMMENT_ERROR'                 => 'Veuillez renseigner un %s valide',
    'COMMENT_ERROR_AUTHOR'          => ['COMMENT_ERROR' => 'nom d\'utilisateur'],
    'COMMENT_ERROR_CONTENT'         => ['COMMENT_ERROR' => 'message'],
    'COMMENT_ERROR_ARTICLE'         => 'Cet article n\'existe pas',
    'COMMENT_ERROR_AUTHOR_LENGTH'   => 'Veuillez renseigner un nom d\'utilisateur de moins de 50 caractères',
    'COMMENT_SUCCESS_MESSAGE'       => 'Votre commentaire a bien été enregistré, il sera visible après validation'
];
