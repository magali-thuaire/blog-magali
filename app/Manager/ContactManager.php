<?php

namespace App\Manager;

use App\Entity\ContactEntity;
use Core\Manager\EntityManager;

class ContactManager extends EntityManager
{
    public function new(ContactEntity $contact): bool
    {
        $qb = $this->createQueryBuilder()
           ->insert('contact')
           ->values('name', 'email', 'message')
        ;
        $statement = $qb->getQuery();
        $attributs = [
            ':name' => $contact->getName(),
            'email' => $contact->getEmail(),
            'message' => $contact->getMessage()
        ];

        return $this->execute($statement, $attributs, true);
    }
}
