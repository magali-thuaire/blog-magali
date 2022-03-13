<?php

namespace App\Entity;

use Core\Entity\CoreUserEntity;
use Core\Trait\HydrateTrait;
use DateTime;
use Exception;
use JetBrains\PhpStorm\Pure;

class UserEntity extends CoreUserEntity
{
    use HydrateTrait;

    private int $id;
    private string $username;
    private string $email;
    private string $password;
    private string $validationToken;
    private bool $userConfirmed;
    private bool $adminValidated;
    private DateTime $createdAt;

    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_SUPERADMIN = 'ROLE_SUPERADMIN';

    private const ERROR_EMAIL = 'Veuillez renseigner un email valide';
    private const ERROR_PASSWORD = 'Veuillez renseigner un mot de passe valide';
    private const ERROR_USERNAME = 'Veuillez renseigner un nom d\'utilisateur valide';
    private const ERROR_USERNAME_LENGTH = 'Veuillez renseigner un nom d\'utilisateur de moins de 50 caractères';

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

    /**
     * @throws Exception
     */
    public function setUsername(?string $username): self
    {
        if (is_string($username) && !empty($username)) {
            if (strlen($username) < 20) {
                $this->username = $username;
            } else {
                throw new Exception(self::ERROR_USERNAME_LENGTH);
            }
        } else {
            throw new Exception(self::ERROR_USERNAME);
        }
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @throws Exception
     */
    public function setEmail(?string $email): self
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;
        } else {
            throw new Exception(self::ERROR_EMAIL);
        }
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @throws Exception
     */
    public function setPassword(?string $password): self
    {
        if (is_string($password) && !empty($password)) {
            $this->password = password_hash($password, PASSWORD_BCRYPT);
        } else {
            throw new Exception(self::ERROR_PASSWORD);
        }
        return $this;
    }

    public function getValidationToken(): ?string
    {
        return $this->validationToken;
    }

    public function setValidationToken(): self
    {
        $this->validationToken = uniqid(rand(), true);
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

    /**
     * @throws Exception
     */
    public function getCreatedAt(): ?DateTime
    {
        if (is_string($this->createdAt)) {
            $this->createdAt = new DateTime($this->createdAt);
        }

        return $this->createdAt;
    }

    /**
     * @throws Exception
     */
    public function setCreatedAt(DateTime|string $createdAt): self
    {
        if (is_string($createdAt)) {
            $createdAt = new DateTime($createdAt);
        }

        $this->createdAt = $createdAt;
        return $this;
    }

    #[Pure] public function isUserValidated(): bool
    {
        return $this->isUserConfirmed() && $this->isAdminValidated();
    }

    #[Pure] public function __toString(): string
    {
        return $this->getUsername();
    }

    public function __sleep(): array
    {
        return [
            'id', 'username', 'email', 'userConfirmed', 'adminValidated', 'role'
        ];
    }
}
