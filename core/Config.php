<?php

namespace Core;

class Config
{
    private ?array $db_config;
    private static $instance;

    public function __construct($db_file)
    {
        $this->db_config = require_once $db_file;
    }

    public static function getInstance($db_file): Config
    {
        if (self::$instance === null) {
            self::$instance = new Config($db_file);
        }

        return self::$instance;
    }

    public function get($key)
    {
        if (!isset($this->db_config[$key])) {
            return null;
        }

        return $this->db_config[$key];
    }
}
