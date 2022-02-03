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

		session_start();

	}

	public static function loadSession()
	{
		session_unset();
		foreach (SESSION as $sessionKey) {
			$_SESSION[$sessionKey] = uniqid(rand(), true);
		}
	}

	public static function not_found()
	{
		require_once VIEWS . '/security/404.html';
	}
}