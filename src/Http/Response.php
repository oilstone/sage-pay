<?php

namespace Oilstone\SagePay\Http;

use Oilstone\SagePay\Exceptions\SagePayException;
use Exception;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Response
 * @package Oilstone\SagePay\Http
 */
class Response
{
    /**
     * @var array
     */
    public static $validStatuses = [
        'ok',
        'ok repeated',
        '3dauth',
        'authenticated',            // 3-D Secure checks carried out and user authenticated correctly.
        'notchecked',               // 3-D Secure checks were not performed. This indicates that 3-D Secure was either switched off at the account level, or disabled at transaction registration with the apply3DSecure set to Disable.
        'cardnotenrolled',          // This means that the card is not in the 3-D Secure scheme.
        'issuernotenrolled',        // This means that the issuer is not part of the 3-D Secure scheme.
        'attemptonly',              // This means that the cardholder attempted to authenticate themselves but the process did not complete. A liability shift may occur for non-Maestro cards, depending on your merchant agreement.
        'incomplete',               // This means that the 3D Secure authentication was not available (normally at the card issuer site).
    ];

    /**
     * @var array
     */
    protected static $responses = [];

    /**
     * @param Exception $exception
     * @throws SagePayException
     */
    public static function exception(Exception $exception)
    {
        $message = "Sage Pay failure";
        $httpStatusCode = 400;
        $errorCode = 1000;

        if (method_exists($exception, 'getResponse')) {
            $response = static::parse($exception->getResponse());

            if (isset($response['code']) && $response['code']) {
                $message = $response['description'];
                $errorCode = $response['code'];
            }

            if (isset($response['errors']) && $response['errors']) {
                $message = $response['errors'][0]['clientMessage'] ?? $response['errors'][0]['description'] . " - " . $response['errors'][0]['property'];
                $errorCode = $response['errors'][0]['code'];
            }

            if (method_exists($exception->getResponse(), 'getStatusCode')) {
                $httpStatusCode = $exception->getResponse()->getStatusCode();
            }
        }

        throw new SagePayException($errorCode, $message, $httpStatusCode, $exception);
    }

    /**
     * @param ResponseInterface $response
     * @return array
     * @throws SagePayException
     */
    public static function parse(ResponseInterface $response): array
    {
        $responseBody = json_decode($response->getBody()->getContents(), true);

        static::$responses[] = $responseBody;

        if (isset($responseBody['status']) && !in_array(Str::lower($responseBody['status']), static::$validStatuses)) {
            $errorCode = intval($responseBody['statusCode'] ?? 1017);
            $message = $responseBody['statusDetail'] ?? 'The transaction was declined';

            throw new SagePayException($errorCode, $message, $response->getStatusCode());
        }

        return $responseBody;
    }

    /**
     * @return array
     */
    public static function responses(): array
    {
        return static::$responses;
    }
}