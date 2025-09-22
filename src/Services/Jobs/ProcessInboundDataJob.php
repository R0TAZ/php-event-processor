<?php

namespace Rotaz\EventProcessor\Services\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Rotaz\EventProcessor\Domains\Models\InboundDataInterface;

/**
 * Represents an abstract job for processing inbound data.
 *
 * This class implements the ShouldQueue interface and includes traits for dispatching,
 * interacting with queues, queuing, and serializing models.
 *
 * Derived classes must provide their own implementation for processing the inbound data.
 *
 * @implements ShouldQueue
 * @uses Dispatchable
 * @uses InteractsWithQueue
 * @uses Queueable
 * @uses SerializesModels
 */
abstract class ProcessInboundDataJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Constructor method.
     *
     * @param InboundDataInterface $inboundData Dependency for handling inbound data.
     * @return void
     */
    public function __construct(
        public InboundDataInterface $inboundData
    ) {
    }
}
