<?php

namespace Oilstone\SagePay\TransactionTypes;

use Oilstone\SagePay\Contracts\TransactionType as TypeContract;
use Oilstone\SagePay\Exceptions\SagePayException;
use Oilstone\SagePay\Registries\CardIdentifier as CardRegistry;
use Oilstone\SagePay\Registries\Session as SessionRegistry;

/**
 * Class Payment
 * @package Oilstone\SagePay\TransactionTypes
 */
class Payment extends Transaction implements TypeContract
{
    /**
     * @param array $transactionDetails
     * @return TypeContract
     * @throws SagePayException
     */
    public function send(array $transactionDetails = []): TypeContract
    {
        $this->transactionDetails = array_merge($this->transactionDetails, $transactionDetails);

        $this->card($this->transactionDetails);

        $transaction = [
            'paymentMethod' => [
                'card' => [
                    'merchantSessionKey' => SessionRegistry::get('merchantSessionKey'),
                    'cardIdentifier' => CardRegistry::get('cardIdentifier'),
                ]
            ],
            'transactionType' => 'Payment',
            'description' => $this->transactionDetails['description'],
            'amount' => ($this->transactionDetails['amount']*1),
            'vendorTxCode' => $this->transactionDetails['vendorTxCode'],
            'currency' => 'GBP',
            'customerFirstName' => $this->transactionDetails['customerFirstName'],
            'customerLastName' => $this->transactionDetails['customerLastName'],
            'billingAddress' => [
                'address1' => $this->transactionDetails['address1'],
                'city' => $this->transactionDetails['city'],
                'postalCode' => $this->transactionDetails['postalCode'],
                'country' => $this->transactionDetails['country'],
            ],
        ];

        if (isset($this->transactionDetails['apply3DSecure'])) {
            $transaction['apply3DSecure'] = $this->transactionDetails['apply3DSecure'];
        }

        if (isset($this->transactionDetails['giftAid'])) {
            $transaction['giftAid'] = $this->transactionDetails['giftAid'];
        }

        if (isset($this->transactionDetails['recurringIndicator'])) {
            $transaction['recurringIndicator'] = $this->transactionDetails['recurringIndicator'];
        }

        if (isset($this->transactionDetails['customerEmail'])) {
            $transaction['billingAddress']['customerEmail'] = $this->transactionDetails['customerEmail'];
        }

        if (isset($this->transactionDetails['customerPhone'])) {
            $transaction['billingAddress']['customerPhone'] = $this->transactionDetails['customerPhone'];
        }

        if (isset($this->transactionDetails['address2'])) {
            $transaction['billingAddress']['address2'] = $this->transactionDetails['address2'];
        }

        if (isset($this->transactionDetails['state'])) {
            $transaction['billingAddress']['state'] = $this->transactionDetails['state'];
        }

        if (isset($this->transactionDetails['recipientLastName']) && isset($this->transactionDetails['shippingAddress1'])) {
            $transaction['shippingDetails'] = [
                'recipientLastName' => $this->transactionDetails['recipientLastName'],
                'shippingAddress1' => $this->transactionDetails['shippingAddress1'] ?? '',
            ];

            if (isset($this->transactionDetails['recipientFirstName'])) {
                $transaction['shippingDetails']['recipientFirstName'] = $this->transactionDetails['recipientFirstName'];
            }

            if (isset($this->transactionDetails['shippingAddress2'])) {
                $transaction['shippingDetails']['shippingAddress2'] = $this->transactionDetails['shippingAddress2'];
            }

            if (isset($this->transactionDetails['shippingCity'])) {
                $transaction['shippingDetails']['shippingCity'] = $this->transactionDetails['shippingCity'];
            }

            if (isset($this->transactionDetails['shippingPostalCode'])) {
                $transaction['shippingDetails']['shippingPostalCode'] = $this->transactionDetails['shippingPostalCode'];
            }

            if (isset($this->transactionDetails['shippingCountry'])) {
                $transaction['shippingDetails']['shippingCountry'] = $this->transactionDetails['shippingCountry'];
            }

            if (isset($this->transactionDetails['shippingState'])) {
                $transaction['shippingDetails']['shippingState'] = $this->transactionDetails['shippingState'];
            }
        }

        $this->transactionResponse = $this->sendBasicAuthRequest('/transactions', $transaction);

        return $this;
    }

    /**
     * @return string
     */
    public function result(): string
    {
        if ($this->transactionResponse['status'] == '3DAuth') {
            return '3d-secure';
        }

        if ($this->transactionResponse['status'] == 'Ok') {
            return 'paid';
        }

        return '';
    }
}