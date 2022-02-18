<?php

// Définition des messages
$contactConst = [
    'CONTACT_ERROR'             => 'Veuillez renseigner un %s valide',
    'CONTACT_ERROR_NAME'        => ['CONTACT_ERROR' => 'nom'],
    'CONTACT_ERROR_EMAIL'       => ['CONTACT_ERROR' => 'email'],
    'CONTACT_ERROR_MESSAGE'     => ['CONTACT_ERROR' => 'message'],
    'CONTACT_SUCCESS_EMAIL'     => 'Votre message a bien été envoyé',
];

$emailConst = [
    'ERROR_SEND_EMAIL'          => 'Problème lors de l\'envoi de l\'email',
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
$postConst = [
    'POST_ERROR_BASE'                       => 'Veuillez renseigner un %s valide',
    'POST_ERROR_TITLE'                      => ['POST_ERROR_BASE' => 'titre'],
    'POST_ERROR_HEADER'                     => ['POST_ERROR_BASE' => 'chapô'],
    'POST_ERROR_CONTENT'                    => ['POST_ERROR_BASE' => 'contenu'],
    'POST_ERROR'                            => 'Cet article n\'existe pas',
    'ADMIN_POST_DELETED_SUCCESS_MESSAGE'    => 'L\'article a été supprimé avec succès',
    'ADMIN_POST_DELETED_ERROR_MESSAGE'      => 'Vous n\'avez pas les droits nécessaires pour supprimer cet article',
    'ADMIN_POST_UPDATE_ERROR_MESSAGE'       => 'Vous n\'avez pas les droits nécessaires pour modifier cet article',
    'ADMIN_POST_UPDATED_SUCCESS_MESSAGE'    => 'L\'article a été mis à jour avec succès',
    'ADMIN_POST_NEW_SUCCESS_MESSAGE'        => 'L\'article a été créé avec succès',
    'ADMIN_POST_NEW_ERROR_MESSAGE'          => 'Un problème est survenu durant la création de l\'article'
];
