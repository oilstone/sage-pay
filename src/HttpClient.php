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
     * @return Client
     */
    public static function make(bool $verifySSL = true): Client
    {
        return new Client(new HttpClientAdapter([
            'verify' => $verifySSL,
        ]));
    }
}
