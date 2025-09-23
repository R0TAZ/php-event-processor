<?php

namespace Rotaz\EventProcessor\Config;

use Illuminate\Contracts\Container\BindingResolutionException;
use Rotaz\EventProcessor\Domains\Profiles\InboundProfile;
use Rotaz\EventProcessor\Domains\Rules\SignatureValidator;
use Rotaz\EventProcessor\Exceptions\InvalidConfig;
use Rotaz\EventProcessor\Services\Jobs\ProcessInboundDataJob;
use Rotaz\EventProcessor\Services\Messages\DefaultInboundResponse;
use Rotaz\EventProcessor\Services\Messages\InboundResponse;


/**
 * Configures the properties for processing inbound events in the system. This class validates
 * and initializes the configuration parameters required to handle events and their related
 * processing.
 */
class EventProcessorConfig
{
    // Introduce constants for config keys to avoid magic strings
    private const KEY_NAME = 'name';
    private const KEY_SIGNING_SECRET = 'signing_secret';
    private const KEY_SIGNATURE_HEADER_NAME = 'signature_header_name';
    private const KEY_SIGNATURE_VALIDATOR = 'signature_validator';
    private const KEY_INBOUND_PROFILE = 'inbound_profile';
    private const KEY_INBOUND_RESPONSE = 'inbound_response';
    private const KEY_INBOUND_DATA_MODEL = 'inbound_data_model';
    private const KEY_STORE_HEADERS = 'store_headers';
    private const KEY_PROCESS_INBOUND_JOB = 'process_inbound_data_job';
    private const KEY_INBOUND_DATA_TRANSFER = 'inbound_data_transfer';

    public readonly string $name;
    public readonly string $signingSecret;
    public readonly string $signatureHeaderName;

    public readonly SignatureValidator $signatureValidator;
    public readonly InboundProfile $inboundProfile;
    public readonly InboundResponse $inboundResponse;
    public readonly string $inboundDataModel;
    /** @var array<string>|string */
    public readonly array|string $storeHeaders;
    public readonly string $processInboundDataJobClass;
    public readonly string $inboundDataTransfer;

    public function __construct(array $properties)
    {
        $this->name = $properties[self::KEY_NAME];
        $this->signingSecret = $properties[self::KEY_SIGNING_SECRET] ?? '';
        $this->signatureHeaderName = $properties[self::KEY_SIGNATURE_HEADER_NAME] ?? '';
        $this->storeHeaders = $properties[self::KEY_STORE_HEADERS] ?? [];
        $this->inboundDataTransfer = $properties[self::KEY_INBOUND_DATA_TRANSFER] ?? '';
        $this->inboundDataModel = $properties[self::KEY_INBOUND_DATA_MODEL];

        if (! is_subclass_of($properties[self::KEY_SIGNATURE_VALIDATOR], SignatureValidator::class)) {
            throw InvalidConfig::invalidSignatureValidator($properties[self::KEY_SIGNATURE_VALIDATOR]);
        }
        $this->signatureValidator = app($properties[self::KEY_SIGNATURE_VALIDATOR]);

        if (! is_subclass_of($properties[self::KEY_INBOUND_PROFILE], InboundProfile::class)) {
            throw InvalidConfig::invalidInboundProfile($properties[self::KEY_INBOUND_PROFILE]);
        }
        $this->inboundProfile = app($properties[self::KEY_INBOUND_PROFILE]);

        $inboundResponseClass = $properties[self::KEY_INBOUND_RESPONSE] ?? DefaultInboundResponse::class;
        if (! is_subclass_of($inboundResponseClass, InboundResponse::class)) {
            throw InvalidConfig::invalidInboundResponse($inboundResponseClass);
        }
        $this->inboundResponse = app($inboundResponseClass);

        if (! is_subclass_of($properties[self::KEY_PROCESS_INBOUND_JOB], ProcessInboundDataJob::class)) {
            throw InvalidConfig::invalidProcessInboundDataJob($properties[self::KEY_PROCESS_INBOUND_JOB]);
        }
        $this->processInboundDataJobClass = str($properties[self::KEY_PROCESS_INBOUND_JOB]);

    }

}