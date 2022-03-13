<?php

namespace Core\Entity;

class CoreUserEntity
{
    protected string $role;
    public const ROLE_USER = 'ROLE_USER';

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        if (is_null($role)) {
            $this->role = static::ROLE_USER;
        } else {
            $this->role = $role;
        }
        return $this;
    }
}
