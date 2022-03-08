<?php

namespace App;

use Core\Config;
use Core\Database\MysqlDatabase;
use Core\Manager\EntityManager;
use Core\Renderer\TwigRenderer;
use Core\Service\Session;
use stdClass;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class App
{
    private static $instance;
    private $db_instance;
    private $renderer;
    public static $config;
    public static $render_config;

    public static function getInstance(): App
    {
        if (self::$instance === null) {
            self::$instance = new App();
        }

        return self::$instance;
    }

    public function getDb(): MysqlDatabase
    {
        $db_config = Config::getInstance(self::$config['CONFIG_DB']);

        if ($this->db_instance === null) {
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
        if ($this->renderer === null) {
            $this->renderer = new TwigRenderer(self::$config['VIEWS']);
        }
        return $this->renderer;
    }

    private function setConfig()
    {
        $const = [];
        $scandir = scandir($prefix = '../_config/define');

        foreach ($scandir as $define) {
            if (!in_array($define, ['.', '..'])) {
                $const[] = require_once $prefix . '/' . $define;
            }
        }

        foreach ($const as $array) {
            array_map(function ($key, $value) {
                if ($value instanceof stdClass) {
                    $value = (array) $value;
                    $value = self::$config[current($value)] . next($value);
                } elseif (is_array($value) && (array_values($value) !== $value)) {
                    $value = sprintf(self::$config[array_key_first($value)], current($value));
                }
                self::$config[$key] =  $value;
            }, array_keys($array), $array);
        }

        self::$render_config = [
            'config'            => App::$config
        ];
    }

    public static function load()
    {
        /**
         * Chargement des fichiers de configuration
         */
        App::getInstance()->setConfig();

        /**
         * Chargement des autoloaders
         */
        require_once self::$config['ROOT'] . '/vendor/autoload.php';

        session_start();

        if (Session::get('user-created')) {
            if (time() - Session::get('user-created') > 1800) {
                Session::unset('user');
                Session::unset('user-created');
            }
        }
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function notFound()
    {
        header('HTTP/1.0 404');
        return $this->getRenderer()->render('/security/404.twig', App::$render_config);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function forbidden()
    {
        header('HTTP/1.0 403');
        return $this->getRenderer()->render('/security/403.twig', App::$render_config);
    }
}
