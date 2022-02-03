<?php

namespace App\Manager;

use App\Entity\ContactEntity;
use Core\Manager\EntityManager;

class ContactManager extends EntityManager
{

	public function new(ContactEntity $contact)
	{
		return $this->execute(
			"INSERT INTO contact(name, email, message) VALUES (:name, :email, :message);",
			[
				'name'    => $contact->getName(),
				'email'   => $contact->getEmail(),
				'message' => $contact->getMessage(),
			]
		);
	}

}