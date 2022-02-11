<?php

namespace Core\Manager;

use Core\Database\Database;

class EntityManager
{
    protected string|array|false $entity;
    protected Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;

        $part = explode('\\', get_class($this));
        $class_name = end($part);
        $this->entity = str_replace('Manager', 'Entity', $class_name);
    }

    public function execute($statement, $attributs, $insert = false): int|string
    {
        return $this->db->execute($statement, $attributs, $insert);
    }

    public function query($statement, $fetchClass = false): bool|array
    {

        if ($fetchClass) {
            $fetchClass =  str_replace('Manager', 'Entity', get_class($this));
        } else {
            $fetchClass = '';
        }

        return $this->db->query($statement, $fetchClass);
    }

    public function prepare($statement, $attributs, $one = false, $fetchClass = false)
    {

        if ($fetchClass) {
            $fetchClass =  str_replace('Manager', 'Entity', get_class($this));
        } else {
            $fetchClass = '';
        }
        return $this->db->prepare($statement, $attributs, $one, $fetchClass);
    }
}
