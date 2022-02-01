<?php

use Core\Autoloader;

require_once '../_config/_define.php';
require_once '../core/Autoloader.php';
Autoloader::register();

$pages = [
	'homepage' => VIEWS . '/homepage/index.php',
];

// Stocke dans une variable tout ce qui est affiché
ob_start();

require_once $pages['homepage'];

$content = ob_get_clean();

require_once VIEWS . '/base.php';