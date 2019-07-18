<?php

namespace Oilstone\SagePay;

use Http\Adapter\Guzzle6\Client as HttpAdapter;
use Oilstone\SagePay\Http\Client as HttpClientWrapper;
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
        return new Client(new HttpAdapter(new HttpClientWrapper([
            'verify' => $verifySSL,
        ])));
    }
}