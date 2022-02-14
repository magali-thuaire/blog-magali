<?php

$securityConst = [
    'INVALID_CSRF_TOKEN'        => 'CSRF token invalide',
];

$userConst = [
    'INVALID_CREDENTIALS'           => 'Identifiants invalides',
    'USER_ERROR'                    => 'Veuillez renseigner un %s valide',
    'USER_ERROR_LOGIN'              => ['USER_ERROR' => 'email'],
    'USER_ERROR_EMAIL'              => ['USER_ERROR' => 'email'],
    'USER_ERROR_PASSWORD'           => ['USER_ERROR' => 'mot de passe'],
    'USER_ERROR_PASSWORD_CONFIRM'   => 'Veuillez vérifier le mot de passe et sa confirmation',
    'USER_ERROR_AUTHOR_LENGTH'      => 'Veuillez renseigner un nom d\'utilisateur de moins de 50 caractères',
    'USER_ERROR_USERNAME'           => ['USER_ERROR' => 'nom d\'utilisateur'],
    'USER_ERROR_VALIDATION'         => 'Connexion impossible pour le moment',
    'USER_ERROR_EXISTS'             => 'Cet utilisateur existe déjà',
    'USER_SUCCESS_REGISTRATION'     => 'Un email d\'activation de compte vient d\'être envoyé à votre adresse email',
];
