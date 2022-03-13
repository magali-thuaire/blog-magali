<?php

namespace App\Entity;

use Core\Trait\HydrateTrait;
use DateTime;
use Exception;

class CommentEntity
{
    use HydrateTrait;

    private int $id;
    private string $content;
    private PostEntity $post;
    private string $username;
    private DateTime $createdAt;
    private bool $approved;

    private const ERROR_CONTENT = 'Veuillez renseigner un contenu valide';
    private const ERROR_USERNAME = 'Veuillez renseigner un nom d\'utilisateur valide';
    private const ERROR_USERNAME_LENGTH = 'Veuillez renseigner un nom d\'utilisateur de moins de 50 caractères';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @throws Exception
     */
    public function setContent(?string $content): self
    {
        if (is_string($content) && !empty($content)) {
            $this->content = $content;
        } else {
            throw new Exception(self::ERROR_CONTENT);
        }

        $this->content = $content;
        return $this;
    }

    public function getPost(): ?PostEntity
    {
        return $this->post;
    }

    public function setPost(PostEntity $post): self
    {
        $this->post = $post;
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

        $this->username = strtolower($username);
        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
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

    public function isApproved(): bool
    {
        return $this->approved;
    }

    public function setApproved(bool $approved): self
    {
        $this->approved = $approved;
        return $this;
    }
}
