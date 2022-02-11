<?php

namespace App\Entity;

use Core\Model\HydrateTrait;
use Core\Model\MagicTrait;
use DateTime;
use Exception;

class UserEntity
{
    use HydrateTrait;
    use MagicTrait;

    private int $id;
    private string $username;
    private string $email;
    private string $login;
    private string $password;
    private string $validationToken;
    private bool $userConfirmed;
    private bool $adminValidated;
    private DateTime $createdAt;
    private string $role;

    private const ROLE_USER = 'ROLE_USER';
    private const ROLE_ADMIN = 'ROLE_ADMIN';
    private const ROLE_SUPERADMIN = 'ROLE_SUPERADMIN';
    private const ERROR_LOGIN = USER_ERROR_LOGIN;
    private const ERROR_EMAIL = USER_ERROR_EMAIL;
    private const ERROR_PASSWORD = USER_ERROR_PASSWORD;
    private const ERROR_USERNAME_LENGTH = USER_ERROR_AUTHOR_LENGTH;
    private const ERROR_USERNAME = USER_ERROR_USERNAME;

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

    public function getLogin(): ?string
    {
        return $this->login;
    }

    /**
     * @throws Exception
     */
    public function setLogin(?string $login): self
    {
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $this->login = $login;
        } else {
            throw new Exception(self::ERROR_LOGIN);
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

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        if (is_null($role)) {
            $this->role = self::ROLE_USER;
        } else {
            $this->role = $role;
        }
        return $this;
    }

    public function isUserValidated(): bool
    {
        return $this->isUserConfirmed() && $this->isAdminValidated();
    }

    public function __toString(): string
    {
        return $this->getUsername();
    }
}
