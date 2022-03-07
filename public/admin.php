<?php

use App\App;
use App\Controller\Admin\AdminCommentController;
use App\Controller\Admin\AdminPostController;
use App\Controller\Admin\AdminUserController;
use App\Controller\SecurityController;
use App\Security\Security;
use Core\Service\Get;
use Core\Service\Post;

require_once '../app/App.php';
App::$config['ROOT'] = dirname(__DIR__);
App::load();

// Pas d'utilisateur authentifié
if (!Security::getUser()) {
    $controller = new SecurityController();
    return $controller->login();
// Utilisateur authentifié mais sans droits administrateur
} elseif (!Security::isAccessGranted()) {
    return App::getInstance()->forbidden();
}

// Récupération de la page à partir de l'URL
if (!empty(Get::get('p'))) {
    $p = Get::get('p');
} else {
    $p = 'dashboard';
}

$postController = new AdminPostController();
$commentController = new AdminCommentController();
$userController = new AdminUserController();

switch (true) {
    // Demande du tableau de bord
    case $p === 'dashboard':
        $postController->index();
        break;
    case $p === 'post-confirm-delete':
        switch (true) {
            case !empty(Get::get('id')) && is_numeric(Get::get('id')):
                $id = Get::get('id');
                $postController->confirmDelete($id);
                break;
            default:
                $postController->index();
        }
        break;
    case $p === 'post-delete':
        switch (true) {
            case !empty(Get::get('id')) && is_numeric(Get::get('id')) && Post::getAll():
                $id = Get::get('id');
                $postController->delete($id);
                break;
            default:
                $postController->index();
        }
        break;
    case $p === 'post-update':
        switch (true) {
            case !empty(Get::get('id')) && is_numeric(Get::get('id')):
                $id = Get::get('id');
                if (Post::getAll()) {
                    $postController->update($id);
                } else {
                    $postController->change($id);
                }
                break;
            default:
                $postController->index();
        }
        break;
    case $p === 'post-new':
        switch (Post::getAll()) {
            case true:
                $postController->new();
                break;
            default:
                $postController->create();
        }
        break;
    case $p === 'post':
        switch (true) {
            case !empty(Get::get('id')) && is_numeric(Get::get('id')):
                $id = Get::get('id');
                $postController->show($id);
                break;
            default:
                $postController->index();
        }
        break;
    case $p === 'comment':
        $commentController->index();
        break;
    case $p === 'comment-confirm-approve':
        switch (true) {
            case !empty(Get::get('id')) && is_numeric(Get::get('id')):
                $id = Get::get('id');
                $commentController->confirmApprove($id);
                break;
            default:
                $commentController->index();
        }
        break;
    case $p === 'comment-approve':
        switch (true) {
            case !empty(Get::get('id')) && is_numeric(Get::get('id')) && Post::getAll():
                $id = Get::get('id');
                $commentController->approve($id);
                break;
            default:
                $commentController->index();
        }
        break;
    case $p === 'comment-confirm-delete':
        switch (true) {
            case !empty(Get::get('id')) && is_numeric(Get::get('id')):
                $id = Get::get('id');
                $commentController->confirmDelete($id);
                break;
            default:
                $commentController->index();
        }
        break;
    case $p === 'comment-delete':
        switch (true) {
            case !empty(Get::get('id')) && is_numeric(Get::get('id')):
                $id = Get::get('id');
                $commentController->delete($id);
                break;
            default:
                $commentController->index();
        }
        break;
    case $p === 'user':
        switch (Security::isSuperAdmin()) {
            case true:
                $userController->index();
                break;
            default:
                $postController->index();
        }
        break;
    case $p === 'user-confirm-validate':
        switch (Security::isSuperAdmin()) {
            case true:
                switch (true) {
                    case !empty(Get::get('id')) && is_numeric(Get::get('id')):
                        $id = Get::get('id');
                        $userController->confirmValidate($id);
                        break;
                    default:
                        $userController->index();
                }
                break;
            default:
                $userController->index();
        }
        break;
    case $p === 'user-validate':
        switch (Security::isSuperAdmin()) {
            case true:
                switch (true) {
                    case !empty(Get::get('id')) && is_numeric(Get::get('id')):
                        $id = Get::get('id');
                        $userController->validate($id);
                        break;
                    default:
                        $userController->index();
                }
                break;
            default:
                $userController->index();
        }
        break;
    case $p === 'user-confirm-delete':
        switch (Security::isSuperAdmin()) {
            case true:
                switch (true) {
                    case !empty(Get::get('id')) && is_numeric(Get::get('id')):
                        $id = Get::get('id');
                        $userController->confirmDelete($id);
                        break;
                    default:
                        $userController->index();
                }
                break;
            default:
                $userController->index();
        }
        break;
    case $p === 'user-delete':
        switch (Security::isSuperAdmin()) {
            case true:
                switch (true) {
                    case !empty(Get::get('id')) && is_numeric(Get::get('id')):
                        $id = Get::get('id');
                        $userController->delete($id);
                        break;
                    default:
                        $userController->index();
                }
                break;
            default:
                $userController->index();
        }
        break;
    case $p === 'user-update':
        switch (Security::isSuperAdmin()) {
            case !empty(Get::get('id')) && is_numeric(Get::get('id')):
                $id = Get::get('id');
                if (Post::getAll()) {
                    $userController->update($id);
                } else {
                    $userController->change($id);
                }
                break;
            default:
                $userController->index();
        }
        break;
    default:
        $postController->index();
}
