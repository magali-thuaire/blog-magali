<?php

use Core\Config;

define('ROOT', dirname(__DIR__));

require '../core/Config.php';
require '../_config/define/_directory.php';
require '../_config/define/_security.php';
require '../_config/define/_message.php';
require '../_config/define/_assets.php';
require '../_config/define/_route.php';

$array = [
	'directory',
	'token',
	'contactConst',
	'securityConst',
	'emailConst',
	'commentConst',
	'assets',
	'routes',
	'form'
];

$const = [];
foreach ($array as $v) {
	$const = array_merge($const, ${$v});
}

Config::define($const);