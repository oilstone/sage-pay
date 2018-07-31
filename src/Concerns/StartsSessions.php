<?php

namespace Oilstone\SagePay\Concerns;

use Oilstone\SagePay\Session;

trait StartsSessions
{
    /**
     * @return $this
     */
    public function startSession()
    {
        (new Session)->create();

        return $this;
    }
}