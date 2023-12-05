<?php

declare(strict_types=1);


class InvalidJsonException extends Exception
{
    public function __construct($message = "Invalid json", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
