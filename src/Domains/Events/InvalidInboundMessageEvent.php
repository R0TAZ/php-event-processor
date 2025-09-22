<?php

namespace Rotaz\EventProcessor\Domains\Events;

/**
 * @package Rotaz\HexKommo\Domains\Events
 */
// Introduce a simple contract for all domain events in this namespace.
interface Event
{
    public function name(): string;
    public function occurredAt(): \DateTimeImmutable;
}

/**
 * Represents an event triggered when an invalid inbound message is encountered.
 * This event contains details about the reason for invalidation and the raw payload.
 */
final readonly class InvalidInboundMessageEvent implements Event
{
    private \DateTimeImmutable $occurredAt;

    /**
     * @param string $reason
     * @param string $rawPayload
     */
    public function __construct(
        public string $reason,
        public string $rawPayload
    ) {
        $this->occurredAt = new \DateTimeImmutable();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'invalid_inbound_message';
    }

    /**
     * Retrieves the date and time when the event occurred.
     *
     * @return \DateTimeImmutable The timestamp of the occurrence.
     */
    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}