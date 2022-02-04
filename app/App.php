<?php

use App\Autoloader as Autoloader;
use Core\Config;
use Core\Autoloader as CoreAutoloader;
use Core\Database\Database;

class App
{
	private static $_instance;
	private $db_instance;

	public static function getInstance()
	{
		if(is_null(self::$_instance)) {
			self::$_instance = new App();
		}

		return self::$_instance;

	}

	public function getDb()
	{
		$db_config = Config::getInstance(CONFIG_DB);

		if(is_null($this->db_instance)) {
			$this->db_instance = new Database($db_config->get('db_name'), $db_config->get('db_host'), $db_config->get('db_user'), $db_config->get('db_pass'));
		}

		return $this->db_instance;

	}

	public function getManager($entity) {
		$manager_class = 'App\Manager\\' . ucfirst($entity) . 'Manager';
		return new $manager_class($this->getDb());
	}

	public static function load()
	{
		session_start();

		require_once APP . '/Autoloader.php';
		Autoloader::register();
		require_once CORE . '/Autoloader.php';
		CoreAutoloader::register();
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