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
    default:
}
