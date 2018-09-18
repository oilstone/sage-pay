<?php

namespace Oilstone\SagePay\TransactionTypes;

use Oilstone\SagePay\Contracts\TransactionType as TypeContract;
use Oilstone\SagePay\Exceptions\SagePayException;
use Oilstone\SagePay\Http\Response;

/**
 * Class Authorisation
 * @package Oilstone\SagePay\TransactionTypes
 */
class Authorisation extends Transaction implements TypeContract
{
    /**
     * @var string
     */
    protected $transactionId;

    /**
     * @param array $transactionDetails
     * @return TypeContract
     * @throws SagePayException
     */
    public function send(array $transactionDetails = []): TypeContract
    {
        $this->transactionDetails = array_merge($this->transactionDetails, $transactionDetails);

        $this->transactionId = $this->transactionDetails['m_d'];

        $transaction = [
            'paRes' => $this->transactionDetails['pa_res'],
        ];

        $this->transactionResponse = $this->sendBasicAuthRequest("/transactions/{$this->transactionId}/3d-secure", $transaction);

        return $this;
    }

    /**
     * @return bool
     */
    public function succeeded(): bool
    {
        return $this->result() === 'paid';
    }

    /**
     * @return string
     */
    public function status(): string
    {
        return $this->transactionResponse['status'] ?? '';
    }

    /**
     * @return string
     */
    public function result(): string
    {
        if (in_array(strtolower($this->transactionResponse['status']), Response::$validStatuses)) {
            return 'paid';
        }

        return '';
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->transactionId ?? '';
    }
}