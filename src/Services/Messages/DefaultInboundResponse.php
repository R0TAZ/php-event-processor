<?php

namespace Rotaz\EventProcessor\Services\Messages;

use Illuminate\Http\JsonResponse;
use Rotaz\EventProcessor\Config\EventProcessorConfig;

/**
 * Handles incoming requests and provides an appropriate JSON response.
 *
 * This class implements the `InboundResponse` interface and processes requests
 * based on the given configuration, returning a standardized JSON response.
 */
class DefaultInboundResponse implements InboundResponse
{

    /**
     * Handles a given request and processes it according to the specified configuration.
     *
     * @param $request \Illuminate\Http\Request incoming HTTP request to be processed.
     * @param EventProcessorConfig $config Configuration details required for processing the event.
     * @return JsonResponse A JSON response indicating the status or result of the operation.
     */
    public function respondTo(\Illuminate\Http\Request $request, EventProcessorConfig $config): JsonResponse
    {
        return response()->json(['status' => 'ok']);
    }
}
