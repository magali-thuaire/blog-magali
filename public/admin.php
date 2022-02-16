<?php

use App\App;
use App\Controller\Admin\AdminPostController;
use App\Controller\SecurityController;
use App\Security\Security;

require_once '../app/App.php';
App::load();

if (!Security::isAccessGranted()) {
    return App::getInstance()->forbidden();
}

// Récupération de la page à partir de l'URL
if (isset($_GET['p']) && !empty($_GET['p'])) {
    $p = Security::checkInput($_GET['p']);
} else {
    $p = 'dashboard';
}

switch (true) {
    // Demande du tableau de bord
    case $p === 'dashboard':
        $controller = new AdminPostController();
        $controller->index();
        break;
    case $p === 'post-confirm-delete':
        $controller = new AdminPostController();
        switch (true) {
            case isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']):
                $id = Security::checkInput($_GET['id']);
                $controller->confirmDelete($id);
                break;
            default:
                $controller->index();
        }
        break;
    case $p === 'post-delete':
        $controller = new AdminPostController();
        switch (true) {
            case isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']) && $_POST:
                $id = Security::checkInput($_GET['id']);
                $controller->delete($id);
                break;
            default:
                $controller->index();
        }
        break;
    default:
}
