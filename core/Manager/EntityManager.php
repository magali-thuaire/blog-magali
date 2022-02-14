<?php

namespace Core\Manager;

use Core\Database\DatabaseInterface;
use Core\Database\QueryBuilder;
use JetBrains\PhpStorm\Pure;

class EntityManager
{
    protected string $entity;
    protected DatabaseInterface $db;

    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db;

        $part = explode('\\', get_class($this));
        $class_name = end($part);
        $this->entity = str_replace('Manager', 'Entity', $class_name);
    }

    #[Pure] protected function createQueryBuilder(): QueryBuilder
    {
        return new QueryBuilder();
    }

    protected function execute($statement, $attributs, $insert = false): int|string
    {
        return $this->db->execute($statement, $attributs, $insert);
    }

    protected function query($statement, $fetchClass = false): bool|array
    {

        if ($fetchClass) {
            $fetchClass =  str_replace('Manager', 'Entity', get_class($this));
        } else {
            $fetchClass = '';
        }

        return $this->db->query($statement, $fetchClass);
    }

    protected function prepare($statement, $attributs, $one = false, $fetchClass = false)
    {

        if ($fetchClass) {
            $fetchClass =  str_replace('Manager', 'Entity', get_class($this));
        } else {
            $fetchClass = '';
        }
        return $this->db->prepare($statement, $attributs, $one, $fetchClass);
    }
}
