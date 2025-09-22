<?php

namespace Rotaz\EventProcessor\Services\Messages;


use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\HeaderBag;

/**
 * Represents a generic inbound message.
 * Implements the InboundMessageInterface.
 * Provides methods to access the message body, headers, protocol, method, type, timestamp, and unique identifier.
 */
class GenericInboundMessage implements InboundMessageInterface
{
    private readonly string $uuid;
    private readonly ?array $body;
    private readonly ?array $headers;
    private readonly int $timestamp;
    private readonly string $type;
    private readonly string $at;

    private readonly string $method;
    private readonly string $protocol;

    /**
     * Constructor method for initializing the object with provided parameters.
     *
     * @param array|null $body The body of the request or payload.
     * @param array|null $headers The headers associated with the request.
     * @param string|null $type The type or content type for the request.
     * @param string|null $method The HTTP method of the request, such as GET or POST.
     * @param string|null $protocol The protocol version, such as HTTP/1.1.
     *
     * @return void
     */
    public function __construct(?array $body, ?array $headers, ?string $type, ?string $method, ?string $protocol)
    {
        $this->uuid = Str::uuid();
        $this->timestamp = time();
        $this->type = $type;
        $this->method = $method;
        $this->protocol = $protocol;
        $this->body = $body;
        $this->headers = $headers;
    }


    public function body(): array
    {
        return $this->body ?? [];
    }

    public function headers(): array
    {
        return $this->headers ?? [];
    }

    public function protocol(): ?string
    {
        return $this->protocol;
    }

    public function method(): ?string
    {
        return $this->method;
    }

    public function type(): ?string
    {
        return $this->type;
    }

    public function at(): int
    {
        return $this->timestamp;
    }

    public function uuid(): string
    {
        return $this->uuid;
    }
}