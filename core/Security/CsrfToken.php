<?php

namespace Core\Security;

use Core\Model\ClassModel;

class CsrfToken
{
	private $id;
	private $value;

	public function __construct(string $id = null, string $value = null)
	{
		$this->id = $id;
		$this->value = $value ?? '';
	}

	public function getId()
	{
		return $this->id;
	}

	public function getValue()
	{
		return $this->value;
	}

	public function __toString()
	{
		return $this->value;
	}
}