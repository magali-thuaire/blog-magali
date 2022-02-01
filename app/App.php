<?php

use App\Autoloader as Autoloader;
use Core\Autoloader as CoreAutoloader;

class App
{
	public static function load()
	{
		require_once APP . '/Autoloader.php';
		Autoloader::register();
		require_once CORE . '/Autoloader.php';
		CoreAutoloader::register();
	}

	public static function not_found()
	{
		require_once VIEWS . '/security/404.html';
	}
}