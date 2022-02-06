<?php

define('ROOT', dirname(__DIR__));
define('APP', ROOT . '/app');
define('VIEWS', APP . '/views');
define('CORE', ROOT . '/core');
define('CONFIG', ROOT . '/_config');
define('PUB', ROOT . '/public');

define('CONFIG_DB', CONFIG . '/_database.php');
define('TEMPLATE','base');

// Nom des token Csrf
define('SESSION', ['contact']);

// Définition des messages
require_once ROOT . '/_config/_message.php';

// Définition des routes
require_once ROOT . '/_config/_route.php';
