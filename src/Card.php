<?php

namespace Oilstone\SagePay;

use Omnipay\Common\CreditCard;

/**
 * Class Card
 * @package Oilstone\SagePay
 */
class Card
{
    /**
     * @param array $cardDetails
     * @return CreditCard
     */
    public static function make(array $cardDetails): CreditCard
    {
        $expiry = str_split($cardDetails['expiryDate'] ?? '', 2);
        $start = str_split($cardDetails['startDate'] ?? '', 2);

        return new CreditCard(array_merge($cardDetails, array_filter([
            'number' => $cardDetails['number'] ?? $cardDetails['cardNumber'] ?? null,
            'expiryMonth' => $cardDetails['expiryMonth'] ?? $expiry[0] ?? null,
            'expiryYear' => $cardDetails['expiryYear'] ?? $expiry[1] ?? null,
            'startMonth' => $cardDetails['startMonth'] ?? $start[0] ?? null,
            'startYear' => $cardDetails['startYear'] ?? $start[1] ?? null,
            'cvv' => $cardDetails['cvv'] ?? $cardDetails['securityCode'] ?? null,
            'billingTitle' => $cardDetails['billingTitle'] ?? $cardDetails['customerTitle'] ?? null,
            'billingFirstName' => $cardDetails['billingFirstName'] ?? $cardDetails['customerFirstName'] ?? null,
            'billingLastName' => $cardDetails['billingLastName'] ?? $cardDetails['customerLastName'] ?? null,
            'billingAddress1' => $cardDetails['billingAddress1'] ?? $cardDetails['address1'] ?? null,
            'billingAddress2' => $cardDetails['billingAddress2'] ?? $cardDetails['address2'] ?? null,
            'billingCity' => $cardDetails['billingCity'] ?? $cardDetails['city'] ?? null,
            'billingPostcode' => $cardDetails['billingPostcode'] ?? $cardDetails['postalCode'] ?? null,
            'billingState' => $cardDetails['billingState'] ?? $cardDetails['state'] ?? null,
            'billingCountry' => $cardDetails['billingCountry'] ?? $cardDetails['country'] ?? null,
            'shippingTitle' => $cardDetails['shippingTitle'] ?? $cardDetails['billingTitle'] ?? $cardDetails['customerTitle'] ?? null,
            'shippingFirstName' => $cardDetails['shippingFirstName'] ?? $cardDetails['billingFirstName'] ?? $cardDetails['customerFirstName'] ?? null,
            'shippingLastName' => $cardDetails['shippingLastName'] ?? $cardDetails['billingLastName'] ?? $cardDetails['customerLastName'] ?? null,
            'shippingAddress1' => $cardDetails['shippingAddress1'] ?? $cardDetails['billingAddress1'] ?? $cardDetails['address1'] ?? null,
            'shippingAddress2' => $cardDetails['shippingAddress2'] ?? $cardDetails['billingAddress2'] ?? $cardDetails['address2'] ?? null,
            'shippingCity' => $cardDetails['shippingCity'] ?? $cardDetails['billingCity'] ?? $cardDetails['city'] ?? null,
            'shippingPostcode' => $cardDetails['shippingPostcode'] ?? $cardDetails['billingPostcode'] ?? $cardDetails['postalCode'] ?? null,
            'shippingState' => $cardDetails['shippingState'] ?? $cardDetails['billingState'] ?? $cardDetails['state'] ?? null,
            'shippingCountry' => $cardDetails['shippingCountry'] ?? $cardDetails['billingCountry'] ?? $cardDetails['country'] ?? null,
            'email' => $cardDetails['email'] ?? $cardDetails['customerEmail'] ?? null,
        ])));
    }
}