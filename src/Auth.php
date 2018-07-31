<?php

namespace Oilstone\SagePay;

use Oilstone\SagePay\Concerns\StartsSessions;
use Oilstone\SagePay\Registries\Authorization;
use Oilstone\SagePay\Registries\Session as SessionRegistry;

/**
 * Class Auth
 * @package Oilstone\SagePay
 */
class Auth
{
    use StartsSessions;

    /**
     * @param string $integrationKey
     * @param string $integrationPassword
     */
    public function basicAuthorizationHeader(string $integrationKey, string $integrationPassword)
    {
        Authorization::store(['header' => 'Basic ' . base64_encode("$integrationKey:$integrationPassword")]);
    }

    /**
     *
     */
    public function tokenAuthorizationHeader()
    {
        $this->startSession();

        Authorization::store(['header' => 'Bearer ' . SessionRegistry::get('merchantSessionKey')]);
    }
}