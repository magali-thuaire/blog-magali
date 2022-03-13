<?php

use App\App;

require_once '../app/App.php';
App::load();
$request = App::request();

// Récupération de la page à partir de l'URL
$p = ($request->get('query', 'p')) ?? 'homepage';

App::$router->match($p, $request, App::getInstance());
