<?php

namespace App\Entity;

use Core\Model\DateTrait;
use Core\Model\HydrateTrait;
use Core\Model\MagicTrait;
use DateTime;

class CommentEntity
{

	const dateFormat = 'd F Y';

	use HydrateTrait;
	use MagicTrait;
	use DateTrait;

	private int $id;
	private string $content;
	private PostEntity $post;
	private UserEntity $author;
	private DateTime $createdAt;
	private bool $approved;

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

	public function setContent(string $content): self
	{
		$this -> content = $content;
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

	public function getAuthor(): ?UserEntity
	{
		return $this -> author;
	}

	public function setAuthor(UserEntity $author): self
	{
		$this -> author = $author;
		return $this;
	}

	public function getCreatedAt(): ?DateTime
	{
		return $this -> createdAt;
	}

	public function getCreatedAtFormatted(): ?string
	{
		if(($date = $this->getCreatedAt()) instanceof DateTime) {
			return $this->dateFormatted($date, self::dateFormat);
		}

		return null;
	}

	public function setCreatedAt(DateTime|string $createdAt): self
	{
		if(is_string($createdAt)) {
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