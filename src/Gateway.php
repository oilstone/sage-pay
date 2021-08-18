<?php

namespace Oilstone\SagePay;

use Oilstone\SagePay\Registries\Config;
use Omnipay\Common\GatewayInterface;
use Omnipay\Omnipay;
use Omnipay\SagePay\ConstantsInterface;

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
    public static function make(array $transactionDetails = []): GatewayInterface
    {
        $testMode = Config::get('environment') === 'TEST';

        return OmniPay::create('SagePay\Direct', HttpClient::make(!$testMode, Config::get('http_config') ?? []))->initialize([
            'vendor' => Config::get('vendor_name'),
            'testMode' => $testMode,
            'apply3DSecure' => $transactionDetails['apply3DSecure'] ?? ConstantsInterface::APPLY_3DSECURE_APPLY,
        ]);
    }
}
