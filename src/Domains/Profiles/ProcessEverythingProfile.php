<?php

namespace Rotaz\EventProcessor\Domains\Profiles;

use Illuminate\Http\Request;

/**
 * Class that determines if a given request should be processed as part of an inbound profile.
 *
 * Implements the logic to evaluate all inbound requests for further handling.
 */
class ProcessEverythingProfile implements InboundProfile
{
    /**
     * Determines whether the given request should be processed.
     *
     * @param Request $request The request to evaluate.
     * @return bool True if the request should be processed, false otherwise.
     */
    public function shouldProcess(Request $request): bool
    {
        return true;
    }
}
