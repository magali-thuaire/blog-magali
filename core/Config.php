<?php

namespace Core;

class Config
{
	private $db_config = [];
	private static $_instance;

	public function __construct($db_file)
	{
		$this->db_config = require_once $db_file;
	}

	public static function getInstance($db_file)
	{
		if(is_null(self::$_instance)) {
			self::$_instance = new Config($db_file);
		}

		return self::$_instance;

	}

	public function get($key)
	{
		if(!isset($this->db_config[$key])) {
			return null;
		}

		return $this->db_config[$key];
	}
}