<?php

namespace Rotaz\EventProcessor\Domains\Rules;


use Rotaz\EventProcessor\Config\EventProcessorConfig;
use Rotaz\EventProcessor\Exceptions\InvalidConfig;

/**
 * Validates the integrity and authenticity of a request's signature.
 *
 * This class implements the SignatureValidator interface and provides a method
 * to verify if a request's signature is valid by comparing it with a computed
 * signature using a signing secret and the HMAC-SHA256 algorithm.
 *
 * The validation process includes:
 * - Extracting the signature from the configured header in the request.
 * - Checking if the signature exists.
 * - Ensuring the signing secret is configured.
 * - Computing the signature using the request content and signing secret.
 * - Comparing the computed signature with the received signature in a secure manner.
 *
 * Throws:
 * - InvalidConfig if the signing secret is not set.
 */
class DefaultSignatureValidator implements SignatureValidator
{
    /**
     * Validates the request against a computed signature to ensure authenticity.
     *
     * @param \Illuminate\Http\Request $request The incoming HTTP request containing the signature to validate.
     * @param EventProcessorConfig $config The configuration object containing the necessary signature verification details.
     * @return bool Returns true if the signature is valid; otherwise, false.
     * @throws InvalidConfig Throws an exception if the signing secret is not set in the configuration.
     */
    public function isValid(\Illuminate\Http\Request $request, EventProcessorConfig $config): bool
    {
        $signature = $request->header($config->signatureHeaderName);

        if (! $signature) {
            return false;
        }

        $signingSecret = $config->signingSecret;

        if (empty($signingSecret)) {
            throw InvalidConfig::signingSecretNotSet();
        }

        $computedSignature = hash_hmac('sha256', $request->getContent(), $signingSecret);

        return hash_equals($computedSignature, $signature);
    }
}
