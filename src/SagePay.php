<?php

namespace Oilstone\SagePay;

use Oilstone\SagePay\Contracts\TransactionType;
use Oilstone\SagePay\DataTypes\Transaction;
use Oilstone\SagePay\Exceptions\SagePayException;
use Oilstone\SagePay\Registries\Config;
use Oilstone\SagePay\TransactionTypes\Authorisation;
use Oilstone\SagePay\TransactionTypes\Payment;
use Oilstone\SagePay\TransactionTypes\Refund;
use Oilstone\SagePay\TransactionTypes\Repeat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Class SagePay
 * @package Oilstone\SagePay
 */
class SagePay
{
    /**
     * SagePay constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        Config::store($config);
    }

    /**
     * @param array $transactionDetails
     * @return TransactionType
     * @throws SagePayException
     */
    public function payment(array $transactionDetails): TransactionType
    {
        return (new Payment($transactionDetails))->send();
    }

    /**
     * @param array $transactionDetails
     * @return TransactionType
     * @throws SagePayException
     */
    public function refund(array $transactionDetails): TransactionType
    {
        return (new Refund($transactionDetails))->send();
    }

    /**
     * @param array $transactionDetails
     * @return TransactionType
     * @throws SagePayException
     */
    public function repeat(array $transactionDetails): TransactionType
    {
        return (new Repeat($transactionDetails))->send();
    }

    /**
     * @param $transactionId
     * @return Transaction
     * @throws SagePayException
     */
    public function transaction($transactionId): Transaction
    {
        return (new Reports())->transactionDetail($transactionId);
    }

    /**
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return Collection
     * @throws SagePayException
     */
    public function transactions(Carbon $startDate, Carbon $endDate): Collection
    {
        return (new Reports())->transactionList($startDate, $endDate);
    }

    /**
     * @param array $authorisationDetails
     * @return TransactionType
     * @throws SagePayException
     */
    public function authorisation(array $authorisationDetails): TransactionType
    {
        return (new Authorisation($authorisationDetails))->send();
    }

    /**
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return Collection
     * @throws SagePayException
     */
    public function batches(Carbon $startDate, Carbon $endDate): Collection
    {
        return (new Reports())->batchList($startDate, $endDate);
    }
}