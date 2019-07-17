<?php

namespace Oilstone\SagePay\TransactionTypes;

use Oilstone\SagePay\Card;
use Oilstone\SagePay\Contracts\TransactionType as TypeContract;
use Oilstone\SagePay\Gateway;

/**
 * Class Payment
 * @package Oilstone\SagePay\TransactionTypes
 */
class Payment extends Transaction implements TypeContract
{
    /**
     * @param array $transactionDetails
     * @return TypeContract
     */
    public function send(array $transactionDetails = []): TypeContract
    {
        $transactionDetails = array_merge($this->transactionDetails, $transactionDetails);

        $gateway = Gateway::make($transactionDetails);
        $card = Card::make($transactionDetails);

        $transaction = $gateway->purchase([
            'amount' => ($transactionDetails['amount'] * 1),
            'currency' => 'GBP',
            'card' => $card,
            'transactionId' => $transactionDetails['vendorTxCode'],
            'description' => $transactionDetails['description'],
            'returnUrl' => $transactionDetails['authorisationCompleteUrl'],
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
            return 'paid';
        }

        if ($this->transactionResponse->isRedirect()) {
            return '3d-secure';
        }

        return '';
    }
}