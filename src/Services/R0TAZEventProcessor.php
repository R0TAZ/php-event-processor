<?php

namespace Rotaz\EventProcessor\Services;

use Exception;
use Illuminate\Http\Request;
use Rotaz\EventProcessor\Config\EventProcessorConfig;
use Rotaz\EventProcessor\Domains\Models\InboundDataInterface;
use Rotaz\EventProcessor\Domains\Events\InvalidInboundSignatureEvent;
use Rotaz\EventProcessor\Exceptions\InvalidInboundSignature;
use Rotaz\EventProcessor\Services\Messages\InboundMessageInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class R0TAZEventProcessor
 * @package Rotaz\EventProcessor\Services
 *
 * Handles event processing for incoming webhook requests.
 *
 * The class is responsible for validating inbound requests, storing data,
 * processing the data asynchronously, and generating a response. It utilizes
 * configurations provided via an EventProcessorConfig instance to determine
 * specific behaviors and actions.
 */
class R0TAZEventProcessor
{
    /**
     * @param Request|InboundMessageInterface $message The incoming message request.
     * @param EventProcessorConfig $config The configuration for the event processor.
     */
    public function __construct(
        protected Request| InboundMessageInterface $message,
        protected EventProcessorConfig $config
    ) {
    }


    /**
     * @return Response
     * @throws InvalidInboundSignature
     */
    public function process(): Response
    {
        $this->ensureValidSignature();

        if (! $this->config->inboundProfile->shouldProcess($this->message)) {
            return $this->createResponse();
        }

        $inboundData = $this->storeInboundData();

        $this->processInboundData($inboundData);

        return $this->createResponse();
    }

    /**
     * Ensures that the signature of the incoming request is valid.
     *
     * Validates the request signature using the configured signature validator.
     * If the signature is invalid, an event is triggered and an exception is thrown.
     *
     * @return self Returns the current instance after ensuring the signature is valid.
     */
    protected function ensureValidSignature(): self
    {
        if (! $this->config->signatureValidator->isValid($this->message, $this->config)) {
            event(new InvalidInboundSignatureEvent($this->message, $this->config));

            throw InvalidInboundSignature::make();
        }

        return $this;
    }

    /**
     * Stores the inbound data from the webhook request.
     *
     * Uses the configured inbound data model to persist the webhook data
     * based on the provided configuration and request.
     *
     * @return InboundDataInterface Returns an instance of the stored inbound data model.
     */
    protected function storeInboundData(): InboundDataInterface
    {
        return $this->config->inboundDataModel::storeInboundData($this->config, $this->message);
    }

    /**
     * Processes the given inbound data by dispatching a job to handle it.
     * Any exception encountered during processing is saved to the inbound data instance
     * and then rethrown for further handling.
     *
     * @param InboundDataInterface $inboundData The inbound data to be processed.
     * @return void This method does not return a value.
     */
    protected function processInboundData(InboundDataInterface $inboundData): void
    {
        try {
            $job = new $this->config->processInboundDataJobClass($inboundData);

            $inboundData->clearException();

            dispatch($job);
        } catch (Exception $exception) {
            $inboundData->saveException($exception);

            throw $exception;
        }
    }

    /**
     * Generates a response by delegating to the inboundResponse object.
     *
     * @return Response The response generated based on the current request and configuration.
     */
    protected function createResponse(): Response
    {
        return $this->config->inboundResponse->respondTo($this->message, $this->config);
    }
}
