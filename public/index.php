<?php

use App\App;
use App\Controller\PostController;
use App\Controller\HomeController as HomeController;
use App\Controller\SecurityController;
use Core\Service\Get;
use Core\Service\Post;

require_once '../app/App.php';
App::$config['ROOT'] = dirname(__DIR__);
App::load();

// Récupération de la page à partir de l'URL
if (!empty(Get::get('p'))) {
    $p = Get::get('p');
} else {
    $p = 'homepage';
}

switch (true) {
    // Demande de la page d'accueil
    case $p === 'homepage':
        $controller = new HomeController();
        $controller->index();
        break;
    // Validation du formulaire de contact
    case $p === 'contact' && Post::getAll():
        $controller = new HomeController();
        $controller->newContact();
        break;
    case $p === 'post':
        $controller = new PostController();
        if (!empty(Get::get('id')) && is_numeric(Get::get('id'))) {
            $id = Get::get('id');
            if (Post::getAll()) {
                // Demande d'ajout d'un commentaire
                $controller->newComment($id);
            } else {
                // Visualisation d'un article
                $controller->show($id);
            }
        } else {
            // Demande de visualisation des articles
            $controller->index();
        }
        break;
    case $p === 'login':
        $controller = new SecurityController();
        if (Post::getAll()) {
            $controller->authenticate();
        } else {
            $controller->login();
        }
        break;
    case $p === 'register':
        $controller = new SecurityController();
        if (Post::getAll()) {
                $controller->register();
        } else {
            $controller->signin();
        }
        break;
    case $p === 'logout':
        $controller = new SecurityController();
        $controller->logout();
        break;
    case $p === 'validate':
        $controller = new SecurityController();
        if (!empty(Get::get('email')) && !empty(Get::get('token'))) {
            $email = Get::get('email');
            $token = Get::get('token');
            $controller->validate($email, $token);
        } else {
            App::getInstance()->notFound();
        }
        break;
    case $p === 'forgot-password':
        $controller = new SecurityController();
        if (Post::getAll()) {
            $controller->emailPassword();
        } else {
            $controller->forgotPassword();
        }
        break;
    case $p === 'reset-password':
        $controller = new SecurityController();
        if (Post::getAll()) {
            $controller->resetPassword();
        } elseif (!empty(Get::get('email')) && !empty(Get::get('token'))) {
            $email = Get::get('email');
            $token = Get::get('token');
            $controller -> newPassword($email, $token);
        } else {
            App ::getInstance() -> notFound();
        }
        break;
    default:
        App::getInstance()->notFound();
}
