<?php

namespace Oilstone\SagePay\Http;

use App\Mail\SagePayAlert;
use Oilstone\SagePay\Exceptions\SagePayException;
use Exception;
use Illuminate\Support\Str;
use Mail;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Response
 * @package Oilstone\SagePay\Http
 */
class Response
{
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

        if (isset($responseBody['status']) && !in_array(Str::lower($responseBody['status']), ['ok', 'ok repeated', '3dauth', 'authenticated'])) {
            $errorCode = $responseBody['statusCode'];
            $message = $responseBody['statusDetail'];

            throw new SagePayException($errorCode, $message, $response->getStatusCode());
        }

        return $responseBody;
    }
}