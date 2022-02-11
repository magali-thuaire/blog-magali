<?php

namespace App\Manager;

use App\Entity\ContactEntity;
use Core\Database\QueryBuilder;
use Core\Manager\EntityManager;
use DateTime;

class ContactManager extends EntityManager
{
    public function new(ContactEntity $contact)
    {
        $statement = $this->newContact()->getQuery();
        $attributs = [
            ':name' => $contact->getName(),
            'email' => $contact->getEmail(),
            'message' => $contact->getMessage()
        ];
        return $this->execute($statement, $attributs);
    }

    private function newContact(): QueryBuilder
    {
        $qb = new QueryBuilder();
        $qb
            ->insert('contact')
            ->values('name', 'email', 'message')
        ;

        return $qb;
    }
}
