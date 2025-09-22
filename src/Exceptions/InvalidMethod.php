<?php

namespace Rotaz\EventProcessor\Exceptions;

use Exception;

/**
 * InvalidMethod
 * Exception thrown when an invalid or disallowed method is invoked.
 */
class InvalidMethod extends Exception
{
    public static function make($method): self
    {
        return new static("The method $method is not allowed.");
    }
}
