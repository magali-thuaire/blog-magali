<?php

namespace App\Manager;

use App\Entity\ContactEntity;
use Core\Manager\EntityManager;

class ContactManager extends EntityManager
{
    //--------------------------------------------------------------
    //------- Requêtes SQL
    //--------------------------------------------------------------

    /**
     * Création d'un nouveau contact
     * @param ContactEntity $contact
     *
     * @return bool
     */
    public function new(ContactEntity $contact): bool
    {
        $qb = $this->createQueryBuilder()
           ->insert('contact')
           ->columns('name', 'email', 'message')
        ;
        $statement = $qb->getQuery();
        $attributs = [
            ':name' => $contact->getName(),
            ':email' => $contact->getEmail(),
            ':message' => $contact->getMessage()
        ];

        return $this->execute($statement, $attributs, true);
    }
}
