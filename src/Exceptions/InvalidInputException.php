<?php

declare(strict_types=1);


class InvalidInputException extends Exception
{
    public function __construct($message = "Invalid input", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
