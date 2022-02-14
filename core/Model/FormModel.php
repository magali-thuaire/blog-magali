<?php

namespace Core\Model;

use Core\Security\CsrfToken;
use Exception;

class FormModel
{
    use HydrateTrait;
    use MagicTrait;

    public $error;
    public $success;
    public CsrfToken $csrfToken;

    private const INVALID_SESSION_TOKEN = 'Invalid Session token';

    /**
     * @throws Exception
     */
    public function __construct(string $keyToken = null)
    {
        if ($keyToken && in_array($keyToken, SESSION)) {
            $csrfToken = new CsrfToken($keyToken, $_SESSION[$keyToken]);
            $this->setCsrfToken($csrfToken);
        } else {
            throw new Exception(self::INVALID_SESSION_TOKEN);
        }
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function setError($error): self
    {
        $this->error = $error;
        return $this;
    }

    public function getSuccess(): ?string
    {
        return $this->success;
    }

    public function setSuccess($success): self
    {
        $this->success = $success;
        return $this;
    }

    public function getCsrfToken(): ?CsrfToken
    {
        return $this->csrfToken;
    }

    public function setCsrfToken(CsrfToken $csrfToken): self
    {
        $this->csrfToken = $csrfToken;
        return $this;
    }

    public function isTokenValid(CsrfToken $csrfToken): bool
    {
        return $csrfToken->getValue() === $this->csrfToken->getValue();
    }
}
