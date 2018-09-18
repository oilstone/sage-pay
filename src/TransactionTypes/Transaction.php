<?php

namespace Oilstone\SagePay\TransactionTypes;

use Oilstone\SagePay\Concerns\CreatesCards;
use Oilstone\SagePay\Concerns\SendsRequests;
use ReflectionClass;

/**
 * Class Transaction
 * @package Oilstone\SagePay\TransactionTypes
 */
abstract class Transaction
{
    use SendsRequests, CreatesCards;

    /**
     * @var array
     */
    protected $transactionDetails;

    /**
     * @var array
     */
    protected $transactionResponse;

    /**
     * Transaction constructor.
     * @param array $transactionDetails
     */
    public function __construct(array $transactionDetails = [])
    {
        $this->transactionDetails = $transactionDetails;
    }

    /**
     * @return array
     */
    public function response(): array
    {
        return $this->transactionResponse;
    }

    /**
     * @return bool
     */
    public function failed(): bool
    {
        return !$this->succeeded();
    }

    /**
     * @return bool
     */
    public function succeeded(): bool
    {
        return ($this->transactionResponse['statusCode'] ?? '') === '0000';
    }

    /**
     * @return string
     */
    public function status(): string
    {
        return $this->transactionResponse['statusDetail'] ?? '';
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->transactionResponse['transactionId'] ?? '';
    }

    /**
     * @return float
     */
    public function amount(): float
    {
        return round($this->transactionResponse['amount']['totalAmount'], 2) ?? 0;
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function type(): string
    {
        return strtolower((new ReflectionClass($this))->getShortName());
    }

    /**
     * @return string
     */
    public function reference(): string
    {
        return $this->transactionDetails['vendorTxCode'] ?? '';
    }
}