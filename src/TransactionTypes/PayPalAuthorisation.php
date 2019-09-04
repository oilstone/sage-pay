<?php

namespace Oilstone\SagePay\TransactionTypes;

use BadMethodCallException;
use Oilstone\SagePay\Contracts\TransactionType as TypeContract;
use Oilstone\SagePay\Gateway;

/**
 * Class PayPalAuthorisation
 * @package Oilstone\SagePay\TransactionTypes
 */
class PayPalAuthorisation extends Transaction implements TypeContract
{
    /**
     * @param array $transactionDetails
     * @return TypeContract
     */
    public function send(array $transactionDetails = []): TypeContract
    {
        $transactionDetails = array_merge($this->transactionDetails, $transactionDetails);

        $gateway = Gateway::make();

        $transaction = $gateway->completePayPal([
            'amount' => $transactionDetails['amount'],
            'transactionId' => $transactionDetails['transactionId'],
            'accept' => $transactionDetails['accept'],
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

        return '';
    }

    /**
     * @return float
     */
    public function amount(): float
    {
        throw new BadMethodCallException('Authorisation transactions do not provide a transaction amount');
    }
}