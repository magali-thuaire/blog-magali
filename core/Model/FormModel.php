<?php

namespace Core\Model;

use Core\Model\ClassModel;

class FormModel extends ClassModel
{
	use MagicTrait;

	protected $error;

	public function getError(): string
	{
		return $this->error;
	}

	public function setError($error): self
	{
		$this->error = $error;
		return $this;
	}

}