<?php

namespace App;

use Core\Config;
use Core\Database\MysqlDatabase;
use Core\Manager\EntityManager;
use Core\Renderer\TwigRenderer;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

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

    public function getDb(): MysqlDatabase
    {
        $db_config = Config::getInstance(CONFIG_DB);

        if (is_null($this->db_instance)) {
            $this->db_instance = new MysqlDatabase(
                $db_config->get('db_name'),
                $db_config->get('db_host'),
                $db_config->get('db_user'),
                $db_config->get('db_pass')
            );
        }

        return $this->db_instance;
    }

    public function getManager($entity): EntityManager
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

    private static function getConfig()
    {
        require_once '../_config/_define.php';
        $const = [];
        foreach ($array as $v) {
            $const = array_merge($const, ${$v});
        }

        Config::define($const);
    }

    public static function load()
    {
        /**
         * Chargement des contantes
         */
        App::getConfig();

        /**
         * Chargement des autoloaders
         */
        require_once ROOT . '/vendor/autoload.php';

        session_start();
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function notFound()
    {
        header('HTTP/1.0 404');
        return $this->getRenderer()->render('/security/404.twig');
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function forbidden()
    {
        header('HTTP/1.0 403');
        return $this->getRenderer()->render('/security/403.twig');
    }
}
