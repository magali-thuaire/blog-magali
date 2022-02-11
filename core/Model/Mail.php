<?php

namespace Core\Model;

class Mail
{
    protected string $to;
    protected string $subject;
    protected string $message;
    protected array $header = [];

    public function setTo($to): self
    {
        $this->to = $to;
        return $this;
    }

    public function setSubject($subject): self
    {
        $this->subject = $subject;
        return $this;
    }

    public function setMessage($message): self
    {
        $this->message = $message;
        return $this;
    }

    public function setHeader($header): self
    {
        $this->header = $header;
        return $this;
    }
}
