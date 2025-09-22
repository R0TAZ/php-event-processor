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

    public readonly object $signatureValidator;
    public readonly object $inboundProfile;
    public readonly InboundResponse $inboundResponse;
    public readonly string $inboundDataModel;
    /** @var array<string>|string */
    public readonly array|string $storeHeaders;
    public readonly string $processInboundDataJobClass;
    public readonly string $inboundDataTransfer;

    /**
     * @param array{
     *     name:string,
     *     signing_secret?:string,
     *     signature_header_name?:string,
     *     signature_validator:class-string<SignatureValidator>,
     *     inbound_profile:class-string<InboundProfile>,
     *     inbound_response?:class-string<InboundResponse>,
     *     inbound_data_model:class-string,
     *     store_headers?:array<string>|string,
     *     process_inbound_job:class-string<ProcessInboundDataJob>
     * } $properties
     * @throws InvalidConfig
     */
    public function __construct(array $properties)
    {
        $this->name = $properties[self::KEY_NAME];
        $this->signingSecret = $properties[self::KEY_SIGNING_SECRET] ?? '';
        $this->signatureHeaderName = $properties[self::KEY_SIGNATURE_HEADER_NAME] ?? '';

        // Extract function: validate and resolve dependencies via container
        $this->signatureValidator = $this->validateAndMake(
            $properties[self::KEY_SIGNATURE_VALIDATOR] ?? null,
            SignatureValidator::class,
            InvalidConfig::class,
            'invalidSignatureValidator'
        );

        $this->inboundProfile = $this->validateAndMake(
            $properties[self::KEY_INBOUND_PROFILE] ?? null,
            InboundProfile::class,
            InvalidConfig::class,
            'invalidInboundProfile'
        );

        $inboundResponseClass = $properties[self::KEY_INBOUND_RESPONSE] ?? DefaultInboundResponse::class;
        $this->inboundResponse = $this->validateAndMake(
            $inboundResponseClass,
            InboundResponse::class,
            InvalidConfig::class,
            'invalidInboundResponse' // fixed method name below
        );

        $this->inboundDataModel = $properties[self::KEY_INBOUND_DATA_MODEL];
        $this->storeHeaders = $properties[self::KEY_STORE_HEADERS] ?? [];
        $this->processInboundDataJobClass = $this->validateJobClass(
            $properties[self::KEY_PROCESS_INBOUND_JOB] ?? null
        );
        $this->inboundDataTransfer = $properties[self::KEY_INBOUND_DATA_TRANSFER] ?? '';
    }

    /**
     * Extracted utility to validate that a class-string implements an interface, then resolve from container.
     *
     * @template T of object
     * @param class-string<T>|null $candidate
     * @param class-string $mustImplement
     * @param class-string $exceptionClass
     * @param string $exceptionFactory
     * @return T
     */
    private function validateAndMake(?string $candidate, string $mustImplement, string $exceptionClass, string $exceptionFactory): object
    {
        if ($candidate === null || ! is_subclass_of($candidate, $mustImplement)) {
            /** @var callable $factory */
            $factory = [$exceptionClass, $exceptionFactory];
            throw $factory($candidate ?? 'null');
        }

        try {
            /** @var object $instance */
            $instance = app($candidate);
            return $instance;
        } catch (BindingResolutionException $e) {
            /** @var callable $factory */
            $factory = [$exceptionClass, $exceptionFactory];
            throw $factory($candidate);
        }
    }

    /**
     * Extracted helper specifically for the job class string; returns class-string after validation.
     *
     * @param class-string<ProcessInboundDataJob>|null $candidate
     * @return class-string<ProcessInboundDataJob>
     * @throws InvalidConfig
     */
    private function validateJobClass(?string $candidate): string
    {
        if ($candidate === null || ! is_subclass_of($candidate, ProcessInboundDataJob::class)) {
            throw InvalidConfig::invalidProcessInboundDataJob($candidate ?? 'null');
        }

        return $candidate;
    }
}