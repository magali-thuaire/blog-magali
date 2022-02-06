<?php

namespace App\Entity;

use Core\Model\DateTrait;
use Core\Model\MagicTrait;
use DateTime;

class PostEntity
{

	use MagicTrait;
	use DateTrait;

	const dateFormat = 'd F Y à H:i';

	private $id;
	private $title;
	private $header;
	private $content;
	// TODO: author depuis user database
	private $author;
	private $published;
	private $publishedAt;
	private $createdAt;
	private $updatedAt;

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

	public function setTitle(string $title): self
	{
		$this->title = $title;
		return $this;
	}

	public function getHeader(): ?string
	{
		return $this->header;
	}

	public function setHeader(string $header): self
	{
		$this->header = $header;
		return $this;
	}

	public function getContent(): ?string
	{
		return $this->content;
	}

	public function setContent(string $content): self
	{
		$this->content = $content;
		return $this;
	}

	public function getAuthor(): ?int
	{
		return $this->author;
	}

	public function setAuthor(int $author): self
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

	public function getPublishedAt(): ?DateTime
	{
		if(!($this->publishedAt instanceof DateTime) && is_string($this->publishedAt)) {
			$this->publishedAt = new DateTime($this->publishedAt);
		}

		return $this->publishedAt;
	}

	public function setPublishedAt(DateTime $publishedAt): self
	{
		$this->publishedAt = $publishedAt;
		return $this;
	}

	public function getCreatedAt(): ?DateTime
	{
		if(!($this->createdAt instanceof DateTime) && is_string($this->createdAt)) {
			$this->createdAt = new DateTime($this->createdAt);
		}
		return $this->createdAt;
	}

	public function setCreatedAt(DateTime $createdAt): self
	{
		$this->createdAt = $createdAt;
		return $this;
	}

	public function getUpdatedAt(): ?DateTime
	{
		if(!($this->updatedAt instanceof DateTime) && is_string($this->updatedAt)) {
			$this->updatedAt = new DateTime($this->updatedAt);
		}

		return $this->updatedAt;
	}

	public function setUpdatedAt(DateTime $updatedAt): self
	{
		$this->updatedAt = $updatedAt;
		return $this;
	}

	public function getCreatedAtFormatted(): ?string
	{
		if(($date = $this->getCreatedAt()) instanceof DateTime) {
			return $this->dateFormatted($date, self::dateFormat);
		}

		return null;
	}

	public function getPublishedAtFormatted(): ?string
	{
		if(($date = $this->getPublishedAt()) instanceof DateTime) {
			return $this->dateFormatted($date, self::dateFormat);
		}

		return null;
	}

	public function getUpdatedAtFormatted(): ?string
	{
		if(($date = $this->getUpdatedAt()) instanceof DateTime) {
			return $this->dateFormatted($date, self::dateFormat);
		}

		return null;
	}
}