<?php

namespace App\Model;

use App\Entity\ContactEntity;
use Core\Model\Mail;

class AppMail extends Mail
{

	public function sendEmail(ContactEntity $contact, $to = EMAIL_DEFAULT_TO, $subject = EMAIL_DEFAULT_SUBJECT): bool
	{
		$this
			->setTo($to)
			->setSubject($subject)
			->setMessage($contact->getMessage())
			->setHeader('From: ' . $contact->getName() . '<' . $contact->getEmail() . '>')
		;

		return mail($this->to, $this->subject, $this->message, $this->header);
	}

}