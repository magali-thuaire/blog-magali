<?php

namespace Core;

use stdClass;

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

//    public static function define(array $const)
//    {
//
//        define('ROOT', dirname(__DIR__));
//
//        array_map(function ($key, $value) {
//            if ($value instanceof stdClass) {
//                $value = (array) $value;
//                $value = constant(current($value)) . next($value);
//            } elseif (is_array($value) && (array_values($value) !== $value)) {
//                return define($key, sprintf(constant(array_key_first($value)), current($value)));
//            }
//            return define($key, $value);
//        }, array_keys($const), $const);
//    }
}
