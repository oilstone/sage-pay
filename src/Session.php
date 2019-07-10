<?php

namespace Oilstone\SagePay;

use Oilstone\SagePay\Concerns\SendsRequests;
use Oilstone\SagePay\Registries\Config;
use Oilstone\SagePay\Registries\Session as SessionRegistry;
use Illuminate\Support\Carbon;

/**
 * Class Session
 * @package Oilstone\SagePay
 */
class Session
{
    use SendsRequests;

    /**
     * @throws Exceptions\SagePayException
     */
    public function create()
    {
        if (!SessionRegistry::get('expiry') || Carbon::parse(SessionRegistry::get('expiry'))->isPast()) {
            SessionRegistry::store($this->sendBasicAuthRequest('/merchant-session-keys', [
                'vendorName' => Config::get('vendor_name'),
            ]));
        }
    }
}