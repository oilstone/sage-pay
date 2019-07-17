<?php

namespace Oilstone\SagePay\TransactionTypes;

use Omnipay\Common\Message\ResponseInterface;
use ReflectionClass;
use ReflectionException;

/**
 * Class Transaction
 * @package Oilstone\SagePay\TransactionTypes
 */
abstract class Transaction
{
    /**
     * @var array
     */
    protected $transactionDetails;

    /**
     * @var ResponseInterface
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
     * @return ResponseInterface
     */
    public function response(): ResponseInterface
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
        return $this->transactionResponse->isSuccessful();
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->transactionResponse->getTransactionReference();
    }

    /**
     * @return string
     * @throws ReflectionException
     */
    public function type(): string
    {
        return strtolower((new ReflectionClass($this))->getShortName());
    }
}