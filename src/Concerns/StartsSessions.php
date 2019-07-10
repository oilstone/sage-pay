<?php

namespace Oilstone\SagePay\Concerns;

use Oilstone\SagePay\Exceptions\SagePayException;
use Oilstone\SagePay\Session;

trait StartsSessions
{
    /**
     * @return $this
     * @throws SagePayException
     */
    public function startSession()
    {
        (new Session)->create();

        return $this;
    }
}