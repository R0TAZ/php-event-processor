<?php

namespace Rotaz\EventProcessor\Services\Messages;

use Illuminate\Http\Request;
use Rotaz\EventProcessor\Config\EventProcessorConfig;

/**
 * Defines the contract for handling and responding to inbound messages.
 *
 * The interface specifies the method that processes an incoming message
 * using the provided configuration and returns a response.
 */
interface InboundResponse
{
    /**
     * Processes the given message based on the provided event processor configuration.
     *
     * @param Request|InboundMessageInterface $message
     * @param EventProcessorConfig $config Configuration settings for processing the event.
     * @return mixed The result of processing the message, the exact type depends on the implementation.
     */
    public function respondTo(Request| InboundMessageInterface $message, EventProcessorConfig $config): mixed;
}
