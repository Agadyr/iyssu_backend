<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ApiException extends Exception
{
    protected string $userMessage;

    public function __construct(string $userMessage, int $code = Response::HTTP_BAD_REQUEST)
    {
        parent::__construct($userMessage, $code);
        $this->userMessage = $userMessage;
    }

    public function getUserMessage(): string
    {
        return $this->userMessage;
    }
}
