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
        switch (true) {
            // Demande de la page d'un article
            case !empty(Get::get('id')) && is_numeric(Get::get('id')):
                $id = Get::get('id');
                switch (Post::getAll()) {
                    // Demande d'ajout d'un commentaire
                    case true:
                        $controller->newComment($id);
                        break;
                    // Visualisation d'un article
                    default:
                        $controller->show($id);
                }
                break;
            // Demande de visualisation des articles
            default:
                $controller->index();
        }
        break;
    case $p === 'login':
        $controller = new SecurityController();
        switch (Post::getAll()) {
            case true:
                $controller->authenticate();
                break;
            default:
                $controller->login();
        }
        break;
    case $p === 'register':
        $controller = new SecurityController();
        switch (Post::getAll()) {
            case true:
                $controller->register();
                break;
            default:
                $controller->signin();
        }
        break;
    case $p === 'logout':
        $controller = new SecurityController();
        $controller->logout();
        break;
    case $p === 'validate':
        $controller = new SecurityController();
        switch (true) {
            case !empty(Get::get('email')) && !empty(Get::get('token')):
                $email = Get::get('email');
                $token = Get::get('token');
                $controller->validate($email, $token);
                break;
            default:
                App::getInstance()->notFound();
        }
        break;
    case $p === 'forgot-password':
        $controller = new SecurityController();
        switch (Post::getAll()) {
            case true:
                $controller->emailPassword();
                break;
            default:
                $controller->forgotPassword();
        }
        break;
    case $p === 'reset-password':
        $controller = new SecurityController();
        switch (Post::getAll()) {
            case true:
                $controller->resetPassword();
                break;
            default:
                switch (true) {
                    case !empty(Get::get('email')) && !empty(Get::get('token')):
                        $email = Get::get('email');
                        $token = Get::get('token');
                        $controller->newPassword($email, $token);
                        break;
                    default:
                        App::getInstance()->notFound();
                }
        }
        break;
    default:
        App::getInstance()->notFound();
}
