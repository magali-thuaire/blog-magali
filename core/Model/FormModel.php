<?php

namespace Core\Model;

use Core\Model\ClassModel;

class FormModel extends ClassModel
{
	use MagicTrait;

	public $error;
	public $success;

	public function getError(): ?string
	{
		return $this->error;
	}

	public function setError($error): self
	{
		$this->error = $error;
		return $this;
	}

	public function getSuccess(): ?string
	{
		return $this->success;
	}

	public function setSuccess($success): self
	{
		$this->success = $success;
		return $this;
	}

}