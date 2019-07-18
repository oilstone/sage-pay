<?php

namespace Oilstone\SagePay\TransactionTypes;

use Oilstone\SagePay\Contracts\TransactionType as TypeContract;
use Oilstone\SagePay\Gateway;

/**
 * Class Refund
 * @package Oilstone\SagePay\TransactionTypes
 */
class Refund extends Transaction implements TypeContract
{
    /**
     * @param array $transactionDetails
     * @return TypeContract
     */
    public function send(array $transactionDetails = []): TypeContract
    {
        $transactionDetails = array_merge($this->transactionDetails, $transactionDetails);

        $gateway = Gateway::make($transactionDetails);

        $transaction = $gateway->refund([
            'transactionReference' => json_encode([
                'VPSTxId' => $transactionDetails['referenceVPSTxId'],
                'VendorTxCode' => $transactionDetails['referenceVendorTxCode'],
                'SecurityKey' => $transactionDetails['referenceSecurityKey'],
                'TxAuthNo' => $transactionDetails['referenceTxAuthNo'],
            ]),
            'transactionId' => $transactionDetails['vendorTxCode'],
            'amount' => $transactionDetails['amount'],
            'description' => $transactionDetails['description'],
            'currency' => $transactionDetails['currency'] ?? 'GBP',
        ]);

        $this->transactionResponse = $transaction->send();

        return $this;
    }

    /**
     * @return string
     */
    public function result(): string
    {
        if ($this->transactionResponse->isSuccessful()) {
            return 'refunded';
        }

        return '';
    }

    /**
     * @return float
     */
    public function amount(): float
    {
        return parent::amount() * -1;
    }
}