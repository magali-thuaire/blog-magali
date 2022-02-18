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
    private string $author;
    private DateTime $createdAt;
    private bool $approved;

    private const ERROR_CONTENT = COMMENT_ERROR_CONTENT;
    private const ERROR_AUTHOR = COMMENT_ERROR_AUTHOR;
    private const ERROR_AUTHOR_LENGTH = COMMENT_ERROR_AUTHOR_LENGTH;

    public function getId(): ?int
    {
        return $this -> id;
    }

    public function setId(int $id): self
    {
        $this -> id = $id;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this -> content;
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
        return $this -> post;
    }

    public function setPost(PostEntity $post): self
    {
        $this -> post = $post;
        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this -> author;
    }

    /**
     * @throws Exception
     */
    public function setAuthor(?string $author): self
    {
        if (is_string($author) && !empty($author)) {
            if (strlen($author) < 20) {
                $this->author = $author;
            } else {
                throw new Exception(self::ERROR_AUTHOR_LENGTH);
            }
        } else {
            throw new Exception(self::ERROR_AUTHOR);
        }

        $this->author = strtolower($author);
        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this -> createdAt;
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
        return $this -> approved;
    }

    public function setApproved(bool $approved): self
    {
        $this -> approved = $approved;
        return $this;
    }
}
