<?php

namespace Rotaz\EventProcessor\Exceptions;

use Exception;

/**
 * InvalidInboundSignature
 * Exception thrown when an inbound signature is invalid.
 */
class InvalidInboundSignature extends Exception
{
    public static function make(): self
    {
        return new static('The signature is invalid.');
    }
}
