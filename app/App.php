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
}