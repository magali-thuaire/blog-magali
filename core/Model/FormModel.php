<?php

namespace Core\Model;

use Core\Security\CsrfToken;
use Core\Service\Session;
use Core\Trait\HydrateTrait;
use Exception;
use JetBrains\PhpStorm\Pure;

class FormModel
{
    use HydrateTrait;

    public $error;
    public $success;
    public CsrfToken $csrfToken;

    /**
     * @throws Exception
     */
    public function __construct(string $keyToken = null, array $messages = [])
    {
        $csrfToken = new CsrfToken($keyToken, Session::get($keyToken));
        $this->setCsrfToken($csrfToken);

        if (!empty($messages)) {
            if (array_key_exists('success', $messages)) {
                $this->setSuccess($messages['success']);
            } elseif (array_key_exists('error', $messages)) {
                $this->setError($messages['error']);
            }
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

    #[Pure] public function isTokenValid(CsrfToken $csrfToken): bool
    {
        return $csrfToken->getValue() === $this->csrfToken->getValue();
    }

    #[Pure] public function hasError(): bool
    {
        return $this->getError() !== null;
    }
}
