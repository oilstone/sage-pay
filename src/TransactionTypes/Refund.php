<?php

namespace Oilstone\SagePay\TransactionTypes;

use Oilstone\SagePay\Contracts\TransactionType as TypeContract;
use Oilstone\SagePay\Exceptions\SagePayException;

/**
 * Class Refund
 * @package Oilstone\SagePay\TransactionTypes
 */
class Refund extends Transaction implements TypeContract
{
    /**
     * @return TypeContract
     * @throws SagePayException
     */
    public function send(): TypeContract
    {
        $transaction = [
            'transactionType' => 'Refund',
            'referenceTransactionId' => $this->transactionDetails['referenceTransactionId'],
            'vendorTxCode' => $this->transactionDetails['vendorTxCode'],
            'amount' => (number_format($this->transactionDetails['amount'],0,'','')*1),
            'description' => $this->transactionDetails['description'],
        ];

        $this->transactionResponse = $this->sendBasicAuthRequest('/transactions', $transaction);

        return $this;
    }

    /**
     * @return string
     */
    public function result(): string
    {
        if ($this->transactionResponse['status'] == 'Ok') {
            return 'refunded';
        }

        return '';
    }

    /**
     * @return float
     */
    public function amount(): float
    {
        return round($this->transactionResponse['amount']['totalAmount'], 2) * -1 ?? 0;
    }
}