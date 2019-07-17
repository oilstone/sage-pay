<?php

namespace Oilstone\SagePay;

use Oilstone\SagePay\Registries\Config;
use Omnipay\Common\GatewayInterface;
use Omnipay\Omnipay;

/**
 * Class Gateway
 * @package Oilstone\SagePay
 */
class Gateway
{
    /**
     * @param array $transactionDetails
     * @return GatewayInterface
     */
    public static function make(array $transactionDetails): GatewayInterface
    {
        return OmniPay::create('SagePay\Direct')->initialize([
            'vendor' => Config::get('vendor_name'),
            'testMode' => (Config::get('environment') ?? Config::get('api_environment') ?? Config::get('reporting_environment')) === 'TEST',
            'apply3DSecure' => $transactionDetails['apply3DSecure'] ?? true,
        ]);
    }
}