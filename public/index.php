<?php

require_once '../_config/_define.php';

$pages = [
	'homepage' => VIEWS . '/homepage/index.php',
];

// Stocke dans une variable tout ce qui est affiché
ob_start();

require_once $pages['homepage'];

$content = ob_get_clean();

require_once VIEWS . '/base.php';