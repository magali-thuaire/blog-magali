<?php

namespace App\Entity;

use Core\Model\HydrateTrait;
use DateTime;

class ContactEntity
{

	use HydrateTrait;

	private $name;
	private $email;
	private $message;
	private $date;

	const ERROR_NAME = CONTACT_ERROR_NAME;
	const ERROR_EMAIL = CONTACT_ERROR_EMAIL;
	const ERROR_MESSAGE = CONTACT_ERROR_MESSAGE;

	public function __construct()
	{
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function getEmail(): ?string
	{
		return $this->email;
	}

	public function getMessage(): ?string
	{
		return $this->message;
	}

	public function setName($name): self
	{
		if(is_string($name) && !empty($name)) {
			$this->name = $name;
		} else {
			throw new \Exception(self::ERROR_NAME);
		}

		return $this;

	}

	public function setEmail($email): self
	{
		if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->email = $email;
		} else {
			throw new \Exception(self::ERROR_EMAIL);
		}
		return $this;
	}

	public function setMessage($message): self
	{
		if(is_string($message) && !empty($message)) {
			$this->message = $message;
		} else {
			throw new \Exception(self::ERROR_MESSAGE);
		}

		return $this;
	}

	public function getDate(): DateTime
	{
		return $this->date;
	}

	public function setDate(DateTime $date): self
	{
		$this->date = $date;
		return $this;
	}

}