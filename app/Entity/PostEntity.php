<?php

namespace App\Entity;

use Core\Trait\HydrateTrait;
use DateTime;
use Exception;

class PostEntity
{
    use HydrateTrait;

    private int $id;
    private string $title = '';
    private string $header = '';
    private string $content = '';
    private UserEntity $author;
    private bool $published = false;
    private ?DateTime $publishedAt;
    private DateTime $createdAt;
    private ?DateTime $updatedAt;
    private array $comments = [];

    private const ERROR_TITLE = POST_ERROR_TITLE;
    private const ERROR_HEADER = POST_ERROR_HEADER;
    private const ERROR_CONTENT = POST_ERROR_CONTENT;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @throws Exception
     */
    public function setTitle(string $title): self
    {
        
        if (!empty($title)) {
            $this->title = $title;
        } else {
            throw new Exception(self::ERROR_TITLE);
        }

        return $this;
    }

    public function getHeader(): ?string
    {
        return $this->header;
    }

    /**
     * @throws Exception
     */
    public function setHeader(string $header): self
    {
        if (!empty($header)) {
            $this->header = $header;
        } else {
            throw new Exception(self::ERROR_HEADER);
        }
        
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @throws Exception
     */
    public function setContent(string $content): self
    {
        if (!empty($content)) {
            $this->content = $content;
        } else {
            throw new Exception(self::ERROR_CONTENT);
        }
        return $this;
    }

    public function getAuthor(): ?UserEntity
    {
        if (!($this->author instanceof UserEntity)) {
            $author = (new UserEntity())->setId((int)$this->author);
            $this->author = $author;
        }

        return $this->author;
    }

    public function setAuthor(UserEntity $author): self
    {
        $this->author = $author;
        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): self
    {
        $this->published = $published;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function getPublishedAt(): ?DateTime
    {
        if (is_string($this->publishedAt)) {
            $this->publishedAt = new DateTime($this->publishedAt);
        }

        return $this->publishedAt;
    }

    /**
     * @throws Exception
     */
    public function setPublishedAt(DateTime|string|null $publishedAt): self
    {
        if (is_string($publishedAt)) {
            $publishedAt = new DateTime($publishedAt);
        }

        $this->publishedAt = $publishedAt;
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

    /**
     * @throws Exception
     */
    public function getUpdatedAt(): ?DateTime
    {
        if (is_string($this->updatedAt)) {
            $this->updatedAt = new DateTime($this->updatedAt);
        }

        return $this->updatedAt;
    }

    /**
     * @throws Exception
     */
    public function setUpdatedAt(DateTime|string|null $updatedAt): self
    {
        if (is_string($updatedAt)) {
            $updatedAt = new DateTime($updatedAt);
        }

        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return CommentEntity[]|null
     */
    public function getComments(): ?array
    {
        return $this->comments;
    }

    public function addComment(CommentEntity $comment): self
    {
        $this->comments[] = $comment;
        return $this;
    }
}
