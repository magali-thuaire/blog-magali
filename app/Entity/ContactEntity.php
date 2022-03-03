<?php

namespace App\Entity;

use Core\Trait\HydrateTrait;
use DateTime;
use Exception;

class ContactEntity
{
    use HydrateTrait;

    private string $name;
    private string $email;
    private string $message;
    private DateTime $date;

    private const ERROR_NAME = 'Veuillez renseigner un nom valide';
    private const ERROR_EMAIL = 'Veuillez renseigner un email valide';
    private const ERROR_MESSAGE = 'Veuillez renseigner un message valide';

    public function __construct()
    {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @throws Exception
     */
    public function setName($name): self
    {
        if (is_string($name) && !empty($name)) {
            $this->name = $name;
        } else {
            throw new Exception(self::ERROR_NAME);
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
    public function setEmail($email): self
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;
        } else {
            throw new Exception(self::ERROR_EMAIL);
        }
        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @throws Exception
     */
    public function setMessage($message): self
    {
        if (is_string($message) && !empty($message)) {
            $this->message = $message;
        } else {
            throw new Exception(self::ERROR_MESSAGE);
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
