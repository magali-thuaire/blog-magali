<?php

define('ROOT', dirname(__DIR__));
define('APP', ROOT . '/app');
define('VIEWS', APP . '/views');
define('CORE', ROOT . '/core');
define('CONFIG', ROOT . '/_config');

define('CONFIG_DB', CONFIG . '/_database.php');
define('TEMPLATE','base');

require_once ROOT . '/_config/_message.php';

define('SESSION', ['contact']);