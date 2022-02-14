<?php

namespace App;

use App\Autoloader as Autoloader;
use Core\Config;
use Core\Autoloader as CoreAutoloader;
use Core\Database\Database;
use Core\Renderer\TwigRenderer;

class App
{
    private static $instance;
    private $db_instance;
    private $renderer;

    public static function getInstance(): App
    {
        if (self::$instance === null) {
            self::$instance = new App();
        }

        return self::$instance;
    }

    public function getDb(): Database
    {
        $db_config = Config::getInstance(CONFIG_DB);

        if (is_null($this->db_instance)) {
            $this->db_instance = new Database(
                $db_config->get('db_name'),
                $db_config->get('db_host'),
                $db_config->get('db_user'),
                $db_config->get('db_pass')
            );
        }

        return $this->db_instance;
    }

    public function getManager($entity)
    {
        $manager_class = 'App\Manager\\' . ucfirst($entity) . 'Manager';
        return new $manager_class($this->getDb());
    }

    public function getRenderer(): TwigRenderer
    {
        if (is_null($this->renderer)) {
            $this->renderer = new TwigRenderer(VIEWS);
        }
        return $this->renderer;
    }

    public static function load()
    {
        session_start();

        require_once APP . '/Autoloader.php';
        Autoloader::register();
        require_once CORE . '/Autoloader.php';
        CoreAutoloader::register();
        require_once ROOT . '/vendor/autoload.php';
    }

    public static function loadSession()
    {
        foreach (SESSION as $sessionKey) {
            unset($_SESSION[$sessionKey]);
            $_SESSION[$sessionKey] = uniqid(rand(), true);
        }
    }

    public static function notFound()
    {
        header('HTTP/1.0 404');
        require_once VIEWS . '/security/404.php';
    }
}
