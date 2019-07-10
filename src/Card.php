<?php

namespace Oilstone\SagePay;

use Oilstone\SagePay\Concerns\SendsRequests;
use Oilstone\SagePay\Registries\CardIdentifier;
use Illuminate\Support\Carbon;

/**
 * Class Card
 * @package Oilstone\SagePay
 */
class Card
{
    use SendsRequests;

    /**
     * @param array $cardDetails
     * @throws Exceptions\SagePayException
     */
    public function create(array $cardDetails)
    {
        if (!CardIdentifier::get('expiry') || Carbon::parse(CardIdentifier::get('expiry'))->isPast()) {
            CardIdentifier::store($this->sendTokenAuthRequest('/card-identifiers', [
                'cardDetails' => $cardDetails
            ]));
        }
    }
}