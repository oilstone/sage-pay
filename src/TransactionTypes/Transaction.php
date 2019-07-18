<?php

namespace Oilstone\SagePay\TransactionTypes;

use BadMethodCallException;
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
     * @return array
     */
    public function response(): array
    {
        return $this->transactionResponse->getData();
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
        $referenceData = $this->transactionResponse->getTransactionReference();

        if (!isset($referenceData)) {
            throw new BadMethodCallException('No valid reference data found. Check the transaction for additional steps such as a redirect request.');
        }

        return str_replace(['{', '}'], '', json_decode($referenceData, true)['VPSTxId'] ?? '');
    }

    /**
     * @return string
     * @throws ReflectionException
     */
    public function type(): string
    {
        return strtolower((new ReflectionClass($this))->getShortName());
    }

    /**
     * @return string
     */
    public function status(): string
    {
        return $this->transactionResponse->getData()['Status'];
    }

    /**
     * @return float
     */
    public function amount(): float
    {
        return round(intval($this->transactionResponse->getRequest()->getParameters()['amount']) / 100, 2);
    }

    /**
     * @return string
     */
    public function reference(): string
    {
        return json_decode($this->transactionResponse->getTransactionReference(), true)['VendorTxCode'];
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->transactionResponse->{$name}(...$arguments);
    }
}