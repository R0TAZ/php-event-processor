<?php

return [
    'configs' => [
        [
            /*
             * This package supports multiple inbound ports endpoints. If you only have
             * one endpoint receiving inbound messages, you can use 'default'.
             */
            'name' => 'default',

            /*
             * We expect that every inbound call will be signed using a secret. This secret
             * is used to verify that the payload has not been tampered with.
             */
            'signing_secret' => env('INBOUND_EVENT_SIGNING_SECRET', 'your-signing-secret'),

            /*
             * The name of the header containing the signature.
             */
            'signature_header_name' => 'Signature',

            /*
             *  This class will verify that the content of the signature header is valid.
             *
             *  \Rotaz\EventProcessor\Domains\Rules\SignatureValidator
             *
             */
            'signature_validator' => \Rotaz\EventProcessor\Domains\Rules\DefaultSignatureValidator::class,

            /*
             * This class determines if the inbound message should be stored and processed.
             */
            'inbound_profile' => \Rotaz\EventProcessor\Domains\Profiles\ProcessEverythingProfile::class,

            /*
             * This class determines the response on a valid inbound data.
             */
            'inbound_response' => \Rotaz\EventProcessor\Services\Messages\DefaultInboundResponse::class,

            /*
             * The classname of the model to be used to store inbound data. The class should
             * be equal or extend \Rotaz\EventProcessor\Domains\Models\AbstractInboundData.
             */
            'inbound_data_model' => \Rotaz\EventProcessor\Domains\Models\AbstractInboundData::class,

            /*
             * In this array, you can pass the headers that should be stored on
             * the inbound data model when a inbound message comes in.
             *
             * To store all headers, set this value to `*`.
             */
            'store_headers' => [

            ],

            /*
             * The class name of the job that will process the inbound request.
             *
             * This should be set to a class that extends Rotaz\EventProcessor\Services\Jobs\ProcessInboundDataJob.
             */
            'process_inbound_data_job' => '',
        ],
    ],

    /*
     * The integer amount of days after which models should be deleted.
     *
     * It deletes all records after 30 days. Set to null if no models should be deleted.
     */
    'delete_after_days' => 30,

    /*
     * Should a unique token be added to the route name
     */
    'add_unique_token_to_route_name' => false,
];
