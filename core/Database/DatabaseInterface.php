<?php

namespace Core\Database;

use PDO;

interface DatabaseInterface
{
    public function getPDO(): PDO;

    public function query($statement, $class = ''): bool|array;

    public function prepare($statement, $attributs, $one = false, $class = '');

    public function execute($statement, $attributs, $insert = false): int|string;
}
