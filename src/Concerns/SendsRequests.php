<?php

namespace Oilstone\SagePay\Concerns;

use GuzzleHttp\Exception\GuzzleException;
use Oilstone\Logging\Log;
use Oilstone\SagePay\Exceptions\SagePayException;
use Oilstone\SagePay\Http\Client;
use Oilstone\SagePay\Http\Response;
use Oilstone\SagePay\Registries\Authorization;
use Oilstone\SagePay\Registries\Config;
use RuntimeException;

/**
 * Trait SendsRequests
 * @package Oilstone\SagePay\Concerns
 */
trait SendsRequests
{
    use SetsAuthorizationHeaders;

    /**
     * @var string
     */
    protected static $logId;

    /**
     * @param string $endPoint
     * @param array $data
     * @param array $headers
     * @return array
     * @throws SagePayException
     */
    public function sendBasicAuthRequest(string $endPoint, array $data = [], array $headers = []): array
    {
        return $this->withBasicAuth()->sendRequest($endPoint, $data, $headers);
    }

    /**
     * @param string $endPoint
     * @param array $data
     * @param array $headers
     * @return array
     * @throws SagePayException
     */
    public function sendRequest(string $endPoint, array $data = [], array $headers = [])
    {
        $client = new Client();
        $response = null;

        try {
            static::generateLogId();

            $this->logRequestStart($endPoint, $data, $this->headers($headers));

            $response = $client->request('POST', Config::get('api_url') . $endPoint, array_filter([
                'headers' => $this->headers($headers),
                'json' => $data,
            ]));

            $this->logRequestFinish();
        } catch (RuntimeException $e) {
            Response::exception($e);
        } catch (GuzzleException $e) {
            Response::exception($e);
        }

        return Response::parse($response);
    }

    /**
     * @return void
     */
    public static function generateLogId()
    {
        if (!static::$logId) {
            static::$logId = uniqid();
        }

        SagePayException::setLogId(static::$logId);
        Response::setLogId(static::$logId);
    }

    /**
     * @param string $endpoint
     * @param array $json
     * @param array $headers
     */
    protected function logRequestStart(string $endpoint, array $json, array $headers)
    {
        if (isset($json['cardDetails'])) {
            $json['cardDetails'] = 'redacted';
        }

        if (isset($json['paymentMethod']['card'])) {
            $json['paymentMethod']['card'] = 'redacted';
        }

        Log::debug(static::$logId . ' - Sage Pay request start', compact('endpoint', 'json', 'headers'));
    }

    /**
     * @param array $params
     * @return array
     */
    public function headers(array $params = []): array
    {
        return array_merge(array_filter([
            'cache-control' => 'no-cache',
            'content-type' => 'application/json',
            'authorization' => Authorization::get('header'),
        ]), $params);
    }

    /**
     * @return void
     */
    protected function logRequestFinish()
    {
        Log::debug(static::$logId . ' - Sage Pay request finish');
    }

    /**
     * @param string $endPoint
     * @param array $data
     * @param array $headers
     * @return array
     * @throws SagePayException
     */
    public function sendTokenAuthRequest(string $endPoint, array $data = [], array $headers = []): array
    {
        return $this->withTokenAuth()->sendRequest($endPoint, $data, $headers);
    }
}