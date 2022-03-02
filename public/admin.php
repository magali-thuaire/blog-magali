<?php

use App\App;
use App\Controller\Admin\AdminCommentController;
use App\Controller\Admin\AdminPostController;
use App\Controller\Admin\AdminUserController;
use App\Controller\SecurityController;
use App\Security\Security;

require_once '../app/App.php';
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
if (isset($_GET['p']) && !empty($_GET['p'])) {
    $p = Security::checkInput($_GET['p']);
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
            case isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']):
                $id = Security::checkInput($_GET['id']);
                $postController->confirmDelete($id);
                break;
            default:
                $postController->index();
        }
        break;
    case $p === 'post-delete':
        switch (true) {
            case isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']) && $_POST:
                $id = Security::checkInput($_GET['id']);
                $postController->delete($id);
                break;
            default:
                $postController->index();
        }
        break;
    case $p === 'post-update':
        switch (true) {
            case isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']):
                $id = Security::checkInput($_GET['id']);
                if ($_POST) {
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
        switch ($_POST) {
            case true:
                $postController->new();
                break;
            default:
                $postController->create();
        }
        break;
    case $p === 'post':
        switch (true) {
            case isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']):
                $id = Security::checkInput($_GET['id']);
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
            case isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']):
                $id = Security::checkInput($_GET['id']);
                $commentController->confirmApprove($id);
                break;
            default:
                $commentController->index();
        }
        break;
    case $p === 'comment-approve':
        switch (true) {
            case isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']) && $_POST:
                $id = Security::checkInput($_GET['id']);
                $commentController->approve($id);
                break;
            default:
                $commentController->index();
        }
        break;
    case $p === 'comment-confirm-delete':
        switch (true) {
            case isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']):
                $id = Security::checkInput($_GET['id']);
                $commentController->confirmDelete($id);
                break;
            default:
                $commentController->index();
        }
        break;
    case $p === 'comment-delete':
        switch (true) {
            case isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']):
                $id = Security::checkInput($_GET['id']);
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
                    case isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']):
                        $id = Security::checkInput($_GET['id']);
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
                    case isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']):
                        $id = Security::checkInput($_GET['id']);
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
                    case isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']):
                        $id = Security::checkInput($_GET['id']);
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
                    case isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']):
                        $id = Security::checkInput($_GET['id']);
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
    default:
        $postController->index();
}
