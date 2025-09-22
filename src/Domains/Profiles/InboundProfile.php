<?php

namespace Rotaz\EventProcessor\Domains\Profiles;

use Illuminate\Http\Request;

/**
 * Interface representing the profile for processing inbound requests.
 */
interface InboundProfile
{

    /**
     * Determines if a given request should be processed.
     *
     * @param Request $request The request object to evaluate.
     * @return bool Returns true if the request should be processed, false otherwise.
     */
    public function shouldProcess(Request $request): bool;
}
