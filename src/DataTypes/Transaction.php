<?php

namespace Oilstone\SagePay\DataTypes;

use Oilstone\SagePay\Contracts\DataType as TypeContract;

/**
 * Class Transaction
 * @package Oilstone\SagePay\DataTypes
 */
class Transaction extends DataType implements TypeContract
{
    /**
     * @return int|null
     */
    public function batchId(): ?int
    {
        return intval($this->batchid) ?: null;
    }
}