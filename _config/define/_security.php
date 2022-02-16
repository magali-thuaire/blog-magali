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
    'USER_ERROR_NOT_EXISTS'         => 'Il n\'y a aucun compte enregistré avec cette adresse e-mail',
    'USER_SUCCESS_REGISTRATION'     => 'Un email d\'activation de compte vient d\'être envoyé à votre adresse email',
    'URL_SITE'                      => 'localhost:8888/blog-magali/public',
    'USER_ACCOUNT_ACTIVATED'        => 'Votre compte a été activé avec succès
                                        Merci d\'attendre la validation de l\'administrateur',
    'USER_PASSWORD_CHANGED'         => 'Votre mot de passe a été réinitialisé. Vous pouvez désormais vous connecter avec votre nouveau mot de passe',
    'USER_TOKEN_INVALID'            => 'Activation de votre compte impossible',
    'USER_PASSWORD_TOKEN_INVALID'   => 'Modification de votre mot de passe impossible',
    'USER_PASSWORD_MODIF_INVALID'   => 'Modification de votre mot de passe impossible',
    'USER_LINK_INVALID'             => 'Votre lien d\'activation a déjà été utilisé',
    'USER_SEND_PASSWORD_EMAIL'      => 'Un email de confirmation vous a été envoyé à cette adresse ',
];
