<?php

namespace App\Entity;

use Core\Model\HydrateTrait;
use Core\Model\MagicTrait;
use DateTime;

class UserEntity
{

	use HydrateTrait;
	use MagicTrait;

	private $id;
	private $username;
	private $email;
	private $login;
	private $password;
	private $validationToken;
	private $userConfirmed;
	private $adminValidated;
	private $createdAt;
	private $role;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function setId(?int $id): self
	{
		$this->id = $id;
		return $this;
	}

	public function getUsername(): ?string
	{
		return $this->username;
	}

	public function setUsername(?string $username): self
	{
		$this->username = $username;
		return $this;
	}

	public function getEmail(): ?string
	{
		return $this->email;
	}

	public function setEmail(?string $email): self
	{
		$this->email = $email;
		return $this;
	}

	public function getLogin(): ?string
	{
		return $this->login;
	}

	public function setLogin(?string $login): self
	{
		$this->login = $login;
		return $this;
	}

	public function getPassword(): ?string
	{
		return $this->password;
	}

	public function setPassword(?string $password): self
	{
		$this->password = $password;
		return $this;
	}

	public function getValidationToken(): ?string
	{
		return $this->validationToken;
	}

	public function setValidationToken(?string $validationToken): self
	{
		$this->validationToken = $validationToken;
		return $this;
	}

	public function isUserConfirmed(): ?bool
	{
		return $this->userConfirmed;
	}

	public function setUserConfirmed(?bool $userConfirmed): self
	{
		$this->userConfirmed = $userConfirmed;
		return $this;
	}

	public function isAdminValidated(): ?bool
	{
		return $this->adminValidated;
	}

	public function setAdminValidated(?bool $adminValidated): self
	{
		$this->adminValidated = $adminValidated;
		return $this;
	}

	public function getCreatedAt(): ?DateTime
	{
		if(is_string($this->createdAt)) {
			$this->createdAt = new DateTime($this->createdAt);
		}

		return $this->createdAt;
	}

	public function setCreatedAt(DateTime|string $createdAt): self
	{
		if(is_string($createdAt)) {
			$createdAt = new DateTime($createdAt);
		}

		$this->createdAt = $createdAt;
		return $this;
	}

	public function getRole(): ?string
	{
		return $this->role;
	}

	public function setRole(?string $role): self
	{
		$this->role = $role;
		return $this;
	}

	public function __toString(): string
	{
		return $this->getUsername();
	}
}