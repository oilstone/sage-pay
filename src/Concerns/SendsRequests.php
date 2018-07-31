<?php

namespace Oilstone\SagePay\Concerns;

use Oilstone\SagePay\Exceptions\SagePayException;
use Oilstone\SagePay\Http\Client;
use Oilstone\SagePay\Http\Response;
use Oilstone\SagePay\Registries\Authorization;
use Oilstone\SagePay\Registries\Config;
use GuzzleHttp\Exception\GuzzleException;
use RuntimeException;

/**
 * Trait SendsRequests
 * @package Oilstone\SagePay\Concerns
 */
trait SendsRequests
{
    use SetsAuthorizationHeaders;

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
            $response = $client->request('POST', Config::get('api_url') . $endPoint, array_filter([
                'headers' => $this->headers($headers),
                'json' => $data,
            ]));
        } catch (RuntimeException $e) {
            Response::exception($e);
        } catch (GuzzleException $e) {
            Response::exception($e);
        }

        return Response::parse($response);
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