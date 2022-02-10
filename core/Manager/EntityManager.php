<?php

namespace Core\Manager;

use Core\Database\Database;

class EntityManager
{

	protected $entity;
	protected $db;

	public function __construct(Database $db)
	{
		$this->db = $db;

		if(is_null($this->entity)) {
			$part = explode('\\', get_class($this));
			$class_name = end($part);
			$this->entity = str_replace('Manager', 'Entity', $class_name);
		}
	}

	public function execute($statement, $attributs) {
		return $this->db->execute($statement, $attributs);
	}

	public function query($statement, $fetchClass = false) {

		if($fetchClass) {
			$fetchClass =  str_replace('Manager', 'Entity', get_class($this));
		} else {
			$fetchClass = '';
		}

		return $this->db->query($statement, $fetchClass);
	}

	public function prepare($statement, $attributs, $one = false, $fetchClass = false) {

		if($fetchClass) {
			$fetchClass =  str_replace('Manager', 'Entity', get_class($this));
		} else {
			$fetchClass = '';
		}
		return $this->db->prepare($statement, $attributs, $one, $fetchClass);

	}

}