<?php

namespace Rotaz\EventProcessor\Services\Port\Http;

use Illuminate\Http\Request;
use Rotaz\EventProcessor\Config\EventProcessorConfig;
use Rotaz\EventProcessor\Services\R0TAZEventProcessor;

/**
 * HttpController serves as an entry point for handling HTTP requests
 * and processing them through the event processor.
 *
 * The controller is designed to be invoked dynamically, taking a request
 * and configuration, and returning an HTTP response after processing.
 *
 *
 * @param Request $request The HTTP request instance to be processed.
 * @param EventProcessorConfig $config The configuration settings for the event processor.
 *
 * @return \Symfony\Component\HttpFoundation\Response The processed HTTP response.
 */
class HttpController
{
    public function __invoke(Request $request, EventProcessorConfig $config): \Symfony\Component\HttpFoundation\Response
    {
        return new R0TAZEventProcessor($request, $config)->process();
    }

}
