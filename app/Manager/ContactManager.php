<?php

namespace App\Manager;

use App\Entity\ContactEntity;
use Core\Manager\EntityManager;
use DateTime;

class ContactManager extends EntityManager
{

	public function new(ContactEntity $contact)
	{

		$contact->setDate(new DateTime());

		return $this->execute(
			"INSERT INTO contact(name, email, message, date) VALUES (:name, :email, :message, :date);",
			[
				'name'    => $contact->getName(),
				'email'   => $contact->getEmail(),
				'message' => $contact->getMessage(),
				'date'	  => $contact->getDate()->format('Y-m-d H:i:s')
			]
		);
	}

}