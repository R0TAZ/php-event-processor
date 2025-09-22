<?php

namespace Rotaz\EventProcessor\Domains\Rules;

use Illuminate\Http\Request;
use Rotaz\EventProcessor\Config\EventProcessorConfig;


/**
 * Interface representing a mechanism to validate request signatures.
 */
interface SignatureValidator
{
    /**
     * Determines if the given request is valid based on the provided configuration.
     *
     * @param Request $request The request to validate.
     * @param EventProcessorConfig $config The configuration used to validate the request.
     * @return bool Returns true if the request is valid, otherwise false.
     */
    public function isValid(Request $request, EventProcessorConfig $config): bool;
}
