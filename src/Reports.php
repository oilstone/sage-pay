<?php

namespace Oilstone\SagePay;

use Oilstone\SagePay\DataTypes\Batch;
use Oilstone\SagePay\DataTypes\Transaction;
use Oilstone\SagePay\Reports\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Sabre\Xml\Service;

/**
 * Class Reports
 * @package Oilstone\SagePay
 */
class Reports extends Service
{
    /**
     * @param $transactionId
     * @return Transaction
     * @throws Exceptions\SagePayException
     */
    public function transactionDetail($transactionId): Transaction
    {
        $transactionData = (new Command('getTransactionDetail', [
            'vpstxid' => $transactionId,
        ]))->send();

        return new Transaction($transactionData);
    }

    /**
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return Collection
     * @throws Exceptions\SagePayException
     */
    public function transactionList(Carbon $startDate, Carbon $endDate): Collection
    {
        $transactionList = (new Command('getTransactionList', [
            'startdate' => $startDate->format('d/m/Y H:i:s'),
            'enddate' => $endDate->format('d/m/Y H:i:s'),
        ]))->send();

        $result = array_map(function ($transaction) {
            return isset($transaction['transaction']) ? new Transaction($transaction['transaction']) : null;
        }, $transactionList['transactions'] ?? []);

        return new Collection(array_values(array_filter($result)));
    }

    /**
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return Collection
     * @throws Exceptions\SagePayException
     */
    public function batchList(Carbon $startDate, Carbon $endDate): Collection
    {
        $batchList = (new Command('getBatchList', [
            'startdate' => $startDate->format('d/m/Y H:i:s'),
            'enddate' => $endDate->format('d/m/Y H:i:s'),
        ]))->send();

        $result = array_map(function ($batch) {
            return isset($batch['batch']) ? new Batch($this->flatten($batch['batch'])) : null;
        }, $batchList['batches'] ?? []);

        return new Collection(array_values(array_filter($result)));
    }

    /**
     * @return string
     * @throws Exceptions\SagePayException
     */
    public function version(): string
    {
        $versionData = (new Command('version'))->send();

        return $versionData['version'];
    }

    /**
     * @param array $data
     * @return array
     */
    protected function flatten(array $data): array
    {
        $flat = [];

        foreach ($data as $idx => $attribute) {
            if(is_array($attribute) && count($attribute) === 1) {
                $value = array_values($attribute)[0];

                if(is_array($value)) {
                    $value = $this->flatten($value);
                }

                $flat[array_keys($attribute)[0]] = $value;
            } else {
                $flat[$idx] = $attribute;
            }
        }

        return $flat;
    }
}