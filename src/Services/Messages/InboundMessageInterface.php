<?php

namespace Rotaz\EventProcessor\Services\Messages;

/**
 * Interface representing an inbound message.
 * Provides methods to access various attributes of the message such as its body, headers, protocol, etc.
 */
interface InboundMessageInterface
{
    public function body();
    public function headers();
    public function protocol();
    public function method();
    public function type();
    public function at();
    public function uuid();

}