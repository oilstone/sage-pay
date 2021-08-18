<?php

namespace Oilstone\SagePay;

use GuzzleHttp\Client as HttpClientAdapter;
use Omnipay\Common\Http\Client;

/**
 * Class HttpClient
 * @package Oilstone\SagePay
 */
class HttpClient
{
    /**
     * @param bool $verifySSL
     * @param array $config
     * @return Client
     */
    public static function make(bool $verifySSL = true, array $config = []): Client
    {
        return new Client(new HttpClientAdapter(array_merge([
            'verify' => $verifySSL,
        ], $config)));
    }
}
