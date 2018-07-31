<?php

namespace Oilstone\SagePay\Concerns;

use Oilstone\SagePay\Auth;
use Oilstone\SagePay\Registries\Config;

trait SetsAuthorizationHeaders
{
    /**
     * @return $this
     */
    public function withBasicAuth()
    {
        $auth = new Auth();

        $auth->basicAuthorizationHeader(Config::get('api_key'), Config::get('api_password'));

        return $this;
    }

    /**
     * @return $this
     */
    public function withTokenAuth()
    {
        $auth = new Auth();

        $auth->tokenAuthorizationHeader();

        return $this;
    }
}