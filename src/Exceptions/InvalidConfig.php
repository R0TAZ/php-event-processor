<?php

namespace Rotaz\EventProcessor\Exceptions;

use Exception;
use Rotaz\EventProcessor\Domains\Profiles\InboundProfile;
use Rotaz\EventProcessor\Domains\Rules\SignatureValidator;
use Rotaz\EventProcessor\Services\Jobs\ProcessInboundDataJob;
use Rotaz\EventProcessor\Services\Messages\InboundResponse;

/**
 * Class InvalidConfig
 * @package Rotaz\EventProcessor\Exceptions
 *
 * Represents an exception related to invalid configuration errors.
 *
 * This class provides static methods to generate specific exception instances
 * for various configuration-related errors. It extends the base Exception class.
 *
 * @author Rodrigo Souza
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class InvalidConfig extends Exception
{
    /**
     * Generates an exception for a missing configuration.
     *
     * @param string $notFoundConfigName The name of the configuration that could not be found.
     * @return self Returns a new instance of the exception indicating the missing configuration.
     */
    public static function couldNotFindConfig(string $notFoundConfigName): self
    {
        return new static("Could not find the configuration for `{$notFoundConfigName}`");
    }

    /**
     * Generates an exception for an invalid signature validator class.
     *
     * @param string $invalidSignatureValidator The name of the class that is not a valid signature validator.
     * @return self Returns a new instance of the exception indicating the invalid signature validator class.
     */
    public static function invalidSignatureValidator(string $invalidSignatureValidator): self
    {
        $signatureValidatorInterface = SignatureValidator::class;

        return new static("`{$invalidSignatureValidator}` is not a valid signature validator class. A valid signature validator is a class that implements `{$signatureValidatorInterface}`.");
    }

    /**
     * Generates an exception for an invalid inbound profile.
     *
     * @param string $inboundProfile The inbound profile class that is invalid.
     * @return self Returns a new instance of the exception indicating the invalid profile class.
     */

    public static function invalidInboundProfile(string $inboundProfile): self
    {
        $inboundProfileInterface = InboundProfile::class;

        return new static("`{$inboundProfile}` is not a valid profile class. A valid profile is a class that implements `{$inboundProfileInterface}`.");
    }

    /**
     * Creates an exception for an invalid webhook response class.
     *
     * @param string $inboundResponse The webhook response class that is invalid.
     * @return self Returns a new instance of the exception for the invalid webhook response.
     */

    public static function invalidInboundResponse(string $inboundResponse): self
    {
        $inboundMessageInterface = InboundResponse::class;

        return new static("`{$inboundResponse}` is not a valid inbound response class. A valid inbound response is a class that implements `{$inboundMessageInterface}`.");
    }

    public static function invalidProcessInboundDataJob(string $processInboundDataJob): self
    {
        $abstractProcessInboundDataJobJob = ProcessInboundDataJob::class;

        return new static("`{$processInboundDataJob}` is not a valid process inbound data job class. A valid class should implement `{$abstractProcessInboundDataJobJob}`.");
    }

    public static function signingSecretNotSet(): self
    {
        return new static('The inbound event signing secret is not set. Make sure that the `signing_secret` config key is set to the correct value.');
    }

    public static function invalidPrunable(mixed $value): self
    {
        return new static("`{$value}` is not a valid amount of days.");
    }
}
