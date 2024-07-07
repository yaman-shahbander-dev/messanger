<?php

namespace App\Helpers;

class OperationResult
{
    private string $state;
    private string $message;
    public function __construct(string $state, string $message)
    {
        $this->state = $state;
        $this->message = $message;
    }

    public function getStatus(): string
    {
        return $this->state;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
