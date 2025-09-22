<?php

namespace Rotaz\EventProcessor\Domains\Events;

use Illuminate\Http\Request;
use Rotaz\EventProcessor\Config\EventProcessorConfig;


/**
 * Represents an invalid inbound signature event within the system.
 *
 * This event is triggered when an invalid signature is detected for an inbound request.
 *
 * Methods and properties allow accessing the associated request and configuration
 * details related to this specific event.
 */
class InvalidInboundSignatureEvent
{
    /**
     * Constructor method.
     *
     * @param Request $request The request instance.
     * @param EventProcessorConfig $config The event processor configuration instance.
     * @return void
     */
    public function __construct(
        public Request $request,
        public EventProcessorConfig $config
    ) {
    }
}
