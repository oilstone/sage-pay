<?php

namespace Oilstone\SagePay\TransactionTypes;

use Oilstone\SagePay\Contracts\TransactionType as TypeContract;
use Oilstone\SagePay\Exceptions\SagePayException;
use Oilstone\SagePay\Http\Response;

/**
 * Class Repeat
 * @package Oilstone\SagePay\TransactionTypes
 */
class Repeat extends Transaction implements TypeContract
{
    /**
     * @return TypeContract
     * @throws SagePayException
     */
    public function send(): TypeContract
    {
        $transaction = [
            'transactionType' => 'Repeat',
            'referenceTransactionId' => $this->transactionDetails['referenceTransactionId'],
            'vendorTxCode' => $this->transactionDetails['vendorTxCode'],
            'amount' => ($this->transactionDetails['amount']*1),
            'currency' => 'GBP',
            'description' => $this->transactionDetails['description'],
        ];

        if (isset($this->transactionDetails['giftAid'])) {
            $transaction['giftAid'] = $this->transactionDetails['giftAid'];
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
        if (in_array(strtolower($this->transactionResponse['status']), Response::$validStatuses)) {
            return 'amended';
        }

        return '';
    }
}