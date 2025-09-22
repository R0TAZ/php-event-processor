<?php

namespace Rotaz\EventProcessor\Domains\Models;

use Exception;
use Illuminate\Http\Request;
use Rotaz\EventProcessor\Config\EventProcessorConfig;

/**
 * Interface InboundDataInterface
 *
 * Represents a contract for handling inbound data operations.
 * An implementation of this interface should define methods
 * necessary for processing or interacting with incoming data streams.
 */
interface InboundDataInterface
{
    public static function storeInboundData(EventProcessorConfig $config, Request $request): InboundDataInterface;

    public function saveException(Exception $exception): InboundDataInterface;

    public function prunable();

    public function clearException(): InboundDataInterface;

}