<?php

namespace Core\Security;

class CsrfToken
{
    private ?string $id;
    private string $value;

    public function __construct(string $id = null, string $value = null)
    {
        $this->id = $id;
        $this->value = $value ?? '';
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->value;
    }
}
